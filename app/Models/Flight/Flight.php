<?php

namespace App\Models\Flight;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Flight extends Model
{
    protected $fillable = ['plan_id', 'flight_at', 'numberOfDrons'];

    /**
     * planテーブルとの接続
     *
     * @return object
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Flight\Plan');
    }

    /**
     * usersテーブルとの接続
     *
     * @return object
     */
	public function users()
	{
		return $this
			->belongsToMany('App\Models\Access\User\User')
			->withPivot('id','environment_id','status');
	}

    /**
     * flightUserテーブルとの接続
     *
     * @return object
     */
	public function flightUser()
	{
		return $this->hasMany('App\Models\Flight\FlightUser');
	}

	//講座を予約しているユーザー数
	public function numberOfUsers(bool $forUpdate = false)
	{
		if ($forUpdate) {
			$numberOfUsers = $this->users()->lockForUpdate()->count();
		} else {
			$numberOfUsers = $this->users()->count();
		}

		return $numberOfUsers;
	}

	//講座を予約しているユーザー数
	public function reachTheLimitOfUsers()
	{
        if ($this->numberOfUsers() >= $this->numberOfDrons) {
            return true;
        }
        else {
			return false;
        }
	}

	//フライト画面へアクセスできる時間
	public function getEnableAccessTime()
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $this->flight_at)
			->subMinute(config('flight.enable_access_flight'));
	}

	//フライト終了時間
	public function getFinishTime()
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $this->flight_at)
			->addMinute(config('flight.flight_time'));
	}

	//キャンセル可能であるか
	public function canCancel()
	{
		$flight_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->flight_at);
        if ($flight_at->subMinute(config('flight.enable_cancel'))->isFuture()) {
            return true;
        }
        else {
			return false;
        }
	}

    /**
     * return true if the flight can be deleted
     * @return bool
     */
	public function canBeDeleted() : bool
	{
		$countUsers = $this->users()->lockForUpdate()->count();
		$isFuture = Carbon::createFromFormat('Y-m-d H:i:s', $this->flight_at)->isFuture();

		if ($countUsers === 0 && $isFuture) {
			return true;
		}

		return false;
	}
}