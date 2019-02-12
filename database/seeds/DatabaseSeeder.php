<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'user'],
            ['id' => 2, 'name' => 'admin']
        ]);

        DB::table('permissions')->insert([
            ['id' => 1, 'slug' => 'view-own-tasks'],
            ['id' => 2, 'slug' => 'create-task'],
            ['id' => 3, 'slug' => 'update-task'],
            ['id' => 4, 'slug' => 'delete-task'],
            ['id' => 5, 'slug' => 'delete-others-tasks'],
            ['id' => 6, 'slug' => 'view-others-tasks'],
            ['id' => 7, 'slug' => 'update-others-tasks'],
            ['id' => 8, 'slug' => 'view-logs'],
        ]);


        DB::table('roles_permissions')->insert([
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 2, 'permission_id' => 5],
            ['role_id' => 2, 'permission_id' => 6],
            ['role_id' => 2, 'permission_id' => 7],
            ['role_id' => 2, 'permission_id' => 8],
        ]);


        DB::table('users')->insert([
            'email' => 'user@gmail.com',
            'password' => app('hash')->make('123456'),
            'role_id' => 1,
        ]);

        DB::table('users')->insert([
            'email' => 'admin@gmail.com',
            'password' => app('hash')->make('123456'),
            'role_id' => 2,
        ]);
    }
}
