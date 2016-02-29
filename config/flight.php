<?php

return array(
	/*
	 * 飛行開始時刻(時)
	 */
	'start_at' => 9,

	/*
	 * 飛行終了時刻(時)
	 */
	'end_at' => 17,

	/*
	 * 1フライトあたりの時間(分)
	 */
	'time' => 20,

	/*
	 * 予約を締め切る時間(分)
	 */
	'reservation_period' => 30,

	/*
	 * フライト画面にアクセスできる時間(分)
	 */
	'enable_access_flight' => 5,

	/*
	 * キャンセルできる時間(分)
	 */
	'enable_cancel' => 180,

	/*
	 * キャンセルできる時間(分)
	 */
	'jwt_key' => env('JWT_KEY', 'test'),

	/*
	 * 予約確認画面における１画面あたりの表示件数
	 */
	'default_per_page' => 15,

	/*
	 * 予約可能上限
	 */
	'limit_of_reservations' => 3,

	/*
	 * 同時に受講できるユーザー数
	 */
	'enable_number_of_users' => 1,

	/*
	 * 
	 */
	'users' => [
	/*
	 * 
	 */
	'default_per_page' => 15,
	],


);