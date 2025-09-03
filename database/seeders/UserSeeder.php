<?php

namespace Admin\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $users = [
            [
                'role_id'    => 1,
                'first_name' => 'Danny',
                'last_name'  => 'Doe',
                'email'      => 'dannydoe@example.com',
                'mobile'     => '9999999999',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id'    => 2,
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'john@example.com',
                'mobile'     => '8888888888',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'role_id'    => 2,
                'first_name' => 'Jane',
                'last_name'  => 'Smith',
                'email'      => 'jane@example.com',
                'mobile'     => '7777777777',
                'status'     => 'inactive',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
