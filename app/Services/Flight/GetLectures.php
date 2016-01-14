<?php namespace App\Services\Flight;

use Carbon\Carbon;

//use App\User;
use App\Models\Access\User\User;
use App\Models\Flight\Flight;
use App\Models\Flight\Plan;
use App\Models\Flight\Type;
use App\Models\Flight\Place;

trait GetLectures {

    public function getLectures($type_id, $place_id, $week)
    {
        //ログイン中のユーザーのIDを取得
        $user_id = \Auth::user()->id;

        $a = config('flight.start_at'); //開始時刻
        $b = config('flight.end_at');   //終了時刻
        $c = config('flight.time');     //1フライトの時間
        $n = ( $b - $a ) * 60 / $c;     //１日のフライト回数

        $base_date = Carbon::now()->startOfWeek()->addWeeks($week);

        $plan_id = Plan::where('type_id', '=', $type_id)
            ->where('place_id', '=', $place_id)
            ->firstOrFail(['id'])
            ->id;

        $first_day = $base_date->copy();           //読み込まれた週の初日(月曜日の00:00)
        $last_day = $base_date->copy()->addDay(7); //読み込まれた週の最終日(日曜日の24:00)

        //OPEN状態のフライト予定を全て取得
        $open_list = Flight::where('flight_at', '>', Carbon::now()->addMinute(config('flight.reservation_period')))
            ->where('flight_at', '>=', $first_day)
            ->where('flight_at', '<', $last_day)
            ->where('plan_id', '=', $plan_id)
            ->get(['id', 'flight_at', 'numberOfDrones'])
            ->toArray();

        //一週間分の日付と曜日を取得　フォーマット："c" => "date weekday", "d" => "10月11日(月)"
        $result["date"] = array();

        //一週間分のtimetableを取得　フォーマット："c" => "rsv rsv-close", "id" => "1", "time" => "09:00"
        $result["timetable"] = array();

        for ($i=0; $i < 7; $i++) {
            if ($first_day->isToday())
            {
                if ($first_day->isWeekday())      $result["date"][$i]["c"] = "date weekday active";
                elseif ($first_day->isSaturday()) $result["date"][$i]["c"] = "date saturday active";
                elseif ($first_day->isSunday())   $result["date"][$i]["c"] = "date sunday active";            
            }
            else
            {
                if ($first_day->isWeekday())      $result["date"][$i]["c"] = "date weekday";
                elseif ($first_day->isSaturday()) $result["date"][$i]["c"] = "date saturday";
                elseif ($first_day->isSunday())   $result["date"][$i]["c"] = "date sunday";
            }

            $date = $first_day->format('m月d日');
            switch ($first_day->dayOfWeek) {
                case 0: $dayOfWeek = "(日)"; break;
                case 1: $dayOfWeek = "(月)"; break;
                case 2: $dayOfWeek = "(火)"; break;
                case 3: $dayOfWeek = "(水)"; break;
                case 4: $dayOfWeek = "(木)"; break;
                case 5: $dayOfWeek = "(金)"; break;
                case 6: $dayOfWeek = "(土)"; break;
            }

            $result["date"][$i]["d"] = $date . $dayOfWeek;

            //$result["timetable"]
            for ($ii=0; $ii < $n; $ii++) {
                $result["timetable"][$i][$ii]["c"] = "0";
                $result["timetable"][$i][$ii]["id"] = "";
                $result["timetable"][$i][$ii]["t"] = "";
            }
            
            //日付を１日進めてループ
            $first_day->addDay();
        }

        foreach ($open_list as $flight) {
            $Y = substr($flight["flight_at"], 0, 4);
            $m = substr($flight["flight_at"], 5, 2);
            $d = substr($flight["flight_at"], 8, 2);
            $hour = substr($flight["flight_at"], 11, 2);
            $minute = substr($flight["flight_at"], 14, 2);
            $hourMinute = substr($flight["flight_at"], 11, 5);
            $day = Carbon::create($Y, $m, $d);

            $i = $day->diffInDays($base_date);
            $ii = (( $hour - $a ) * 60 + $minute ) / $c;

            $result["timetable"][$i][$ii]["id"] = $flight["id"];
            $result["timetable"][$i][$ii]["t"] = $hourMinute;
            $result["timetable"][$i][$ii]["c"] = "3";

            //flightを受講するユーザーの人数を取得
            $reserved = Flight::findorfail($flight["id"])->users;

            if ( $reserved->count() ) {

                if ( $reserved->count() >= $flight["numberOfDrones"] )
                    $result["timetable"][$i][$ii]["c"] = "1";

                foreach ($reserved as $key => $value)
                    if ( $value->id == $user_id )
                        $result["timetable"][$i][$ii]["c"] = "2";
            }            
        }

        return $result;
    }
}


