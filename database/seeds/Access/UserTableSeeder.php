<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder
{
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.users_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.users_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.users_table') . ' CASCADE');
        }

        //Add the master administrator, user id of 1
        $users = [
            [
                'name'              => 'Admin Istrator',
                'first_name'        => 'Admin',
                'last_name'         => 'Istrator',
                'email'             => 'admin@admin.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'View Backend',
                'first_name'        => 'View',
                'last_name'         => 'Backend',
                'email'             => 'view-backend@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'View Access Management',
                'first_name'        => 'View',
                'last_name'         => 'AccessManagement',
                'email'             => 'view-access-management@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Edit Users',
                'first_name'        => 'Edit',
                'last_name'         => 'Users',
                'email'             => 'edit@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Except Permanently Delete Users',
                'first_name'        => 'Except',
                'last_name'         => 'Permanently Delete Users',
                'email'             => 'except@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('123456'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Default User2',
                'first_name'        => 'Default',
                'last_name'         => 'User2',
                'email'             => 'user2@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Default User3',
                'first_name'        => 'Default',
                'last_name'         => 'User3',
                'email'             => 'user3@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Default User4',
                'first_name'        => 'Default',
                'last_name'         => 'User4',
                'email'             => 'user4@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Default User5',
                'first_name'        => 'Default',
                'last_name'         => 'User5',
                'email'             => 'user5@user.com',
                'age'               => '25',
                'sex'               => '0',
                'postal_code'       => '1000014',
                'state'             => '東京都',
                'city'              => '千代田区',
                'street'            => '永田町１丁目７−１',
                'building'          => '',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table(config('access.users_table'))->insert($users);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}