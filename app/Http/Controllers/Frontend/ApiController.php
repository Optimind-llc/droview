<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Frontend\User\UserContract;
use \Firebase\JWT\JWT;
use Carbon\Carbon;
//Request
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Http\Requests\Frontend\User\ChangePasswordRequest;
//Trait
use App\Services\Flight\GetLectures;
use App\Services\Flight\GetReservations;
//Jobs
use App\Jobs\SendConfirmReservationEmail;
use App\Jobs\SendCancelReservationEmail;
use App\Jobs\SendConfirmPaymentEmail;

use App\Models\Access\User\User;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;
use App\Models\Flight\FlightUser;
use App\Models\Ticket;
use App\Models\Pin;
use WebPay\WebPay;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class ApiController extends Controller {

	use GetLectures, GetReservations;

	public function index()
	{
		$domain = env('APP_URL');
		return view('frontend.user.single.index', compact('domain'));
	}

	public function getTimetable(Request $inputs)
	{
		$result = $this->getLectures($inputs['flightType'], $inputs['place'], $inputs['week']);
		return \Response::json($result);
	}

	public function getDefaultStatus()
	{
		$result['selector']['types'] = Type::all(['id', 'name', 'en']);
		$result['selector']['places'] = Place::all(['id', 'path']);
		$result['selector']['plans'] = Plan::all(['type_id', 'place_id']);

		$min_type_id = Plan::min('type_id');
		$min_place_id = Plan::where('type_id', '=', $min_type_id)->min('place_id');
		$week = '0';
		$result['timetable']['key'] = $min_type_id . '_' . $min_place_id . '_' . $week;
		$result['timetable']['data'] = $this->getLectures($min_type_id, $min_place_id, $week);
		return \Response::json($result);
	}

	public function getUserInfo()
	{
		$result = \Auth::user()->toArray();
		unset($result['confirmation_code'], $result['confirmed'], $result['created_at'], $result['deleted_at'], $result['updated_at'], $result['status']);
		$result['status']['reservations'] = \Auth::user()->numberOfReserved();
		$result['status']['remainingTickets'] = \Auth::user()->remainingTickets();

		$result['auth'] = array();
		foreach (\Auth::user()->providers as $p) {
			array_push($result['auth'], $p['provider']);
        }
		return \Response::json($result);
	}

	public function updateUserProf(UserContract $user, UpdateProfileRequest $request)
	{
        $user->updateProfile(access()->id(), $request->all());
		$result['userProf'] = \Auth::user()->toArray();
		unset(
            $result['userProf']['confirmation_code'],
            $result['userProf']['confirmed'],
            $result['userProf']['created_at'],
            $result['userProf']['deleted_at'],
            $result['userProf']['updated_at'],
            $result['userProf']['status']
        );

        $result['msg'] = array(
            'type' => 'success',
            'msg' => 'プロフィールを更新しました'
        );

		return \Response::json($result);
	}

    public function changePassword(UserContract $user, ChangePasswordRequest $request)
    {
        $user->changePassword($request->all());
        $msg = array(
            'type' => 'success',
            'msg' => 'パスワードを変更しました'
        );

        return \Response::json($msg);
    }

	public function getReservationList()
	{
        $result = $this->getReservations();
		return \Response::json($result);
	}

	public function getTestToken(Request $inputs)
	{
		//未処理のチケット消費を実行
		\Auth::user()->consumptionTicket();

		$res = array();
		$res['msg'] = $this->validateReservation($inputs['id']);

		if ($res['msg']['type'] === 'success') {
			$user = \Auth::user();
			$key = config('flight.jwt_key');
			$value = array(
				'test' => 'test',
				'flight_id' => $inputs['id'],
	        	'name' => $user->name,
				'email' => $user->email,
				'start' => 'test',
				'end' => 'test',
				'result' => array()
			);
			$res['jwt'] = JWT::encode($value, $key);
		}

        return \Response::json($res);
	}

	public function reserve(Request $inputs)
	{
		$res = array();
		$key = config('flight.jwt_key');
		$decoded = (array) JWT::decode($inputs['token'], $key, array('HS256'));
		//try catchで認証失敗を追加
		$flight_id = $decoded['flight_id'];
		$res['msg'] = $this->validateReservation($flight_id);

		if ($res['msg']['type'] === 'success') {
			$res['msg']['msg'] = '予約が完了しました';
			$user = \Auth::user();

			//情報を追加してJWTを再生成
			$plan = Flight::findOrFail($flight_id)
				->plan()
				->first(['type_id', 'place_id']);
			$decoded['type'] = Type::findOrFail($plan['type_id'])->name;
			$decoded['place'] = Place::findOrFail($plan['place_id'])->name;
			$decoded['start'] = Flight::findOrFail($flight_id)
				->getEnableAccessTime()
				->timestamp;
			$decoded['end'] = Flight::findOrFail($flight_id)
				->getFinishTime()
				->timestamp;
			$jwt_token = JWT::encode($decoded, $key);
	
			//予約情報をデータベースに保存
	        \DB::table('flight_user')->insert([
	            'user_id' => $user->id,
	            'flight_id' => $flight_id,
	            'environment_id' => '1',
	            'status' => '0',
	            'jwt' => $jwt_token
	        ]);

	        //FlightUserのidをJWTのキーとして利用
	        $unique_id = FlightUser::where('user_id', '=', $user->id)
				->where('flight_id', '=', $flight_id)
				->first()
				->id;

			//JWT、予約実行後の予約数をレスポンスに格納
			$res['jwt'] = array($unique_id => $jwt_token);
			$res['reservations'] = \Auth::user()->numberOfReserved();

	        //Queue jobを使ってメール送信
	        $this->dispatch(new SendConfirmReservationEmail($user));
		}

        return \Response::json($res);
	}

	protected function getJwt()
	{
		$jwts = \Auth::user()->getJwt();
		return \Response::json($jwts);
	}

	protected function validateReservation($flight_id)
	{
        $user = \Auth::user();

        if ($user->reachTheLimitOfReservations()){
            $msg = array(
            	'type' => 'error',
            	'msg' => '予約可能数が上限に達しています');
        } else if ($user->alreadyReservedAtSameTime($flight_id)){
            $msg = array(
            	'type' => 'error',
            	'msg' => '同じ時刻に別の予約があります');
        } else if ($user->notHaveEnoughTickets())
        {
            $msg = array(
            	'type' => 'error',
            	'msg' => '所持チケットが足りません');
        } else {
            $msg = array(
            	'type' => 'success',
            	'msg' => '');
		}

        return $msg;
	}

    public function cancel(Request $inputs)
    {
        $id = $inputs['id'];
        $flight_id = FlightUser::findOrFail($id)->flight_id;

        if (Flight::findOrFail($flight_id)->canCancel()) {
        	$user = \Auth::user();

	        \DB::table('flight_user')->where('id', '=', $id)->delete();
	        $msg = array(
	        	'type' => 'success',
	        	'msg' => '予約をキャンセルしました'
	        );
	        $res['msg'] = $msg;
	        $res['reservations'] = $user->numberOfReserved();
	        $res['data'] = $this->getReservations();

	        //Queue jobを使ってメール送信
	        $this->dispatch(new SendCancelReservationEmail($user));

	        return \Response::json($res);
        } else {
	        $msg = array(
	        	'type' => 'error',
	        	'msg' => 'キャンセル可能時刻を過ぎています'
	        );
	        $res['msg'] = $msg;

	        return \Response::json($res);        	
        }
    }

	public function getLog()
	{
        $user = \Auth::user();
        $tickets = $user->tickets()->get(['amount','created_at','method'])->toArray();
        return \Response::json($tickets);
	}

    public function addByWebpay(Request $inputs)
    {
        $amount = $inputs['amount'];
        $token = $inputs['webpay-token'];
        $msg = array();
        $user = \Auth::user();
        $tickets = $user->remainingTickets();

		try {
		    $webpay = new WebPay('test_secret_gyJ9Sr6dO5P22Ma8d6cHXf2N');
		    $webpay->charge->create(array(
		       'amount'=> $amount,
		       'currency'=>'jpy',
		       'card'=> $token,
		       'description'=>''
		    ));
   		} catch (\WebPay\ErrorResponse\ErrorResponseException $e) {
   			$msg['type'] = 'error';
		    $error = $e->data->error;
		    switch ($error->causedBy) {
		        case 'buyer':
		            // カードエラーなど、購入者に原因がある
		            // エラーメッセージをそのまま表示するのがわかりやすい
		        	$msg['msg'] = $error->message;
		            break;
		        case 'insufficient':
		            // 実装ミスに起因する
		        	$msg['msg'] = $error->message;
		            break;
		        case 'missing':
		            // リクエスト対象のオブジェクトが存在しない
		        	$msg['msg'] = $error->message;
		            break;
		        case 'service':
		            // WebPayに起因するエラー
		        	$msg['msg'] = $error->message;
		            break;
		        default:
		            // 未知のエラー
		        	$msg['msg'] = $error->message;
		            break;
		    }
		    $result = array(
    			'tickets' => $tickets,
    			'msg' => $msg
    		);
		    return \Response::json($result);
		} catch (\WebPay\ApiException $e) {
		    // APIからのレスポンスが受け取れない場合。接続エラーなど
		} catch (\Exception $e) {
		    // WebPayとは関係ない例外の場合
		}

		$this->addticket($inputs['num'], 'webpay', '');
		//チケットを追加したのでオブジェクトを更新
        $user = \Auth::user();
        $tickets = $user->remainingTickets();
        $msg = array(
    		'type' => 'success',
    		'msg' => 'チケットを購入しました'
    	);
    	$result = array(
    		'tickets' => $tickets,
    		'msg' => $msg
    	);

        //Queue jobを使ってメール送信
        $this->dispatch(new SendConfirmPaymentEmail($user));

        return \Response::json($result);
    }

    public function addByPin(Request $inputs)
    {
        $pinCode = $inputs['pin'];
        $pin = Pin::where('pin', '=', $pinCode)->first();
        $numberOfTickets = $pin->numberOfTickets;
        $user = \Auth::user();
        $tickets = $user->remainingTickets();

        if (!isset($pin))
        {
        	$msg = array(
    			'type' => 'error',
    			'msg' => 'このチケットは利用できません'
    		);
		    $result = array(
    			'tickets' => $tickets,
    			'msg' => $msg
    		);
		    return \Response::json($result);
        }

        if ($pin->status === '0')
        {
            $pin->status = '1';
            $pin->save();
            $this->addticket($numberOfTickets, 'pin', $pinCode);
			//チケットを追加したのでオブジェクトを更新
	        $user = \Auth::user();
	        $tickets = $user->remainingTickets();
        	$msg = array(
    			'type' => 'success',
    			'msg' => 'チケットを追加しました'
    		);
		    $result = array(
    			'tickets' => $tickets,
    			'msg' => $msg
    		);

	        //Queue jobを使ってメール送信
	        $this->dispatch(new SendConfirmPaymentEmail($user));

		    return \Response::json($result);
        }

    	$msg = array(
			'type' => 'error',
			'msg' => 'このチケットは既に使用されています'
		);
	    $result = array(
			'tickets' => $tickets,
			'msg' => $msg
		);
	    return \Response::json($result);
    }

    public function addticket($numberOfTickets, $method, $key)
    {
        $user = \Auth::user();

        $ticket = new Ticket;
        $ticket->user_id = $user->id;
        $ticket->amount = $numberOfTickets;
        $ticket->method = $method;
        $ticket->key = $key;
        $ticket->save();
    }

    public function test()
	{
		return view('frontend.user.single.test');
	}

	public function flight()
	{
		$flights = \Auth::user()->flights()->get(['flight_id'])->toArray();
		$now = Carbon::now();

		foreach ($flights as $flight) {
			if (Flight::findOrFail($flight['flight_id'])->getEnableAccessTime()->isPast() &&
				Flight::findOrFail($flight['flight_id'])->getFinishTime()->isFuture()) {
				return view('frontend.user.single.flight2');
			}
		}

		return redirect()->back();
	}

    public function getAddress(Request $inputs)
	{
		$post1 = $inputs['post1'];
		$post2 = $inputs['post2'];

		try {
			ob_start();
		    $res = file_get_contents('http://api.thni.net/jzip/X0401/JSON/' . $post1 . '/' . $post2 . '.js');
		    $warning = ob_get_contents();
		    ob_end_clean();
		    if ($warning) {
		        throw new Exception($warning);
		    }
		    return response($res);
		} catch (Exception $e) {
			$msg = array(
    			'type' => 'error',
    			'msg' => '存在しない郵便番号です'
    		);
			return response($msg);
		}
	}

}
