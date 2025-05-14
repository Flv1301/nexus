<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(CaseTypesSeeder::class);
        $this->call(UnitySeeder::class);
        $this->call(SectorSeeder::class);
        $this->call(UserSeeder::class);
    }
}
