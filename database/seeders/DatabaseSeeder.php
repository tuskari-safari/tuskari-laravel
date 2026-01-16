<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserRoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CmsPageSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(PageSeeder::class);

        // Safari platform data
        $this->call(SafariDataSeeder::class);
    }
}
