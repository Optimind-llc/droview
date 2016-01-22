<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RolePermissionTableSeeder
 */
class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.permission_role_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.permission_role_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.permission_role_table') . ' CASCADE');
        }

        //Attach admin role to admin user
        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(2)->attachPermission(1);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(3)->attachPermission(1);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(3)->attachPermission(2);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(4)->attachPermission(1);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(4)->attachPermission(2);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(4)->attachPermission(4);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(1);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(2);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(3);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(4);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(5);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(6);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(7);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(8);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(9);

        $user_model = config('auth.providers.roles.model');
        $user_model = new $user_model;
        $user_model::find(5)->attachPermission(11);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}