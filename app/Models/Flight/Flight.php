<?php

namespace App\Models\Flight;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Flight extends Model
{
	use SoftDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = ['plan_id', 'flight_at', 'numberOfDrons'];

    /**
     * 日付により変更を起こすべき属性
     *
     * @var array
     */
    protected $dates = ['flight_at', 'deleted_at'];

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
		return $this->belongsToMany('App\Models\Access\User\User');
	}

    /**
     * return true if the flight can be Canceled
     *
     * @return bool
     */
	public function canBeCanceled()
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
     *
     * @return bool
     */
	public function canBeDeleted() : bool
	{
		$countUsers = $this->users()->lockForUpdate()->count();
		$isFuture = $this->flight_at->isFuture();

		if ($countUsers === 0 && $isFuture) {
			return true;
		}

		return false;
	}
}