<?php

namespace Packages\Admin\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeedUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'status' => 1,
            ],
            [
                'name' => 'Seller',
                'slug' => 'seller',
                'status' => 1,
            ],
        ];
    
        foreach ($roles as $role) {
            DB::table('user_roles')->updateOrInsert(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'slug' => $role['slug'],
                    'status' => $role['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        $this->command->info('Customer role seeded successfully.');
    }
}
