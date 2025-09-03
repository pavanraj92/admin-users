<?php

namespace Admin\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Admin\Users\Database\Seeders\SeedUserRolesSeeder;
use Admin\Users\Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            SeedUserRolesSeeder::class,
            UserSeeder::class,
        ]);
    }
}
