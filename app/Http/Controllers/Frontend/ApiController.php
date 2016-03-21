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
//Exceptions
use App\Exceptions\NotFoundException;
//Models
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
		$env = env('APP_ENV');
		return view('frontend.user.single.index', compact('domain', 'env'));
	}

	public function getUserInfo()
	{
		$user = \Auth::user();
        foreach ($user->roles as $role) {
            $role->permissions;
        }
		$result = $user->toArray();
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
		$limit = Carbon::now()->subMinute(config('flight.flight_time'));
		$flights = \Auth::user()
			->flights()
			->with('plan.type', 'plan.place')
			->where('flight_at', '>=', $limit)
			->get();

		return \Response::json(['reservations' => $flights]);

  //       $result = $this->getReservations();
		// return \Response::json($result);
	}

    public function cancel(Request $inputs)
    {
        $id = $inputs['id'];
        $flight = Flight::with('plan.type', 'plan.place')->find($id);

        if (is_null($flight)) {
        	throw new NotFoundException('flight.notFound');
        }

        if ($flight->flight_at->subMinute(config('flight.enable_cancel'))->isPast()) {
        	throw new NotFoundException('cancel.fail.overLimit');
        }

    	$user = \Auth::user();
    	$user->flights()->detach($id);

        //Queue jobを使ってメール送信
        // $this->dispatch(new SendCancelReservationEmail($user));

		$limit = Carbon::now()->subMinute(config('flight.flight_time'));
		$flights = $user
			->flights()
			->with('plan.type', 'plan.place')
			->where('flight_at', '>=', $limit)
			->get();

        return \Response::json(['reservations' => $flights]);
    }

	public function getLog()
	{
        $user = \Auth::user();
        $tickets = $user->tickets()->get(['amount','created_at','method'])->toArray();
        return \Response::json(['ticketLogs' => $tickets]);
	}

    public function addByWebpay(Request $inputs)
    {
        $amount = $inputs['amount'];
        $token = $inputs['webpay_token'];
        $msg = array();
        $user = \Auth::user();
        $tickets = $user->remainingTickets();

		try {
		    $webpay = new WebPay('test_secret_gkI1q4bQQ3nOdCwgF8dVbeg5');
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
		    return \Response::json($e, 500);
		} catch (\Exception $e) {
		    // WebPayとは関係ない例外の場合
		    return \Response::json($e, 500);
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
        //$this->dispatch(new SendConfirmPaymentEmail($user));

        return \Response::json($result);
    }

    public function addByPin(Request $inputs)
    {
        $pinCode = $inputs['pin'];
        $pin = Pin::where('pin', '=', $pinCode)->first();
        $numberOfTickets = $pin->numberOfTickets;
        $user = \Auth::user();
        $tickets = $user->remainingTickets();
        //return \Response::json($pin->status);

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

        if ($pin->status === 0)
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
