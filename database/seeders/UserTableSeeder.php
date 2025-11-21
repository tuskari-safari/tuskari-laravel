<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = User::create([
            'first_name' => "Admin",
            'last_name' => "Admin",
            'email' => "admin@admin.com",
            'password' => "11111111",
            'active' => true
        ]);
        $superAdminUser->assignRole('SUPER-ADMIN');
        $user = User::create([
            'first_name' => "User",
            'last_name' => "User",
            'email' => "user@yopmail.com",
            'password' => "11111111",
            'active' => true
        ]);
        $user->assignRole('TRAVELLER');
        $user = User::create([
            'first_name' => "SAFARI",
            'last_name' => "OPERATOR",
            'email' => "operator@yopmail.com",
            'password' => "11111111",
            'active' => true
        ]);
        $user->assignRole('SAFARI_OPERATOR');
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('TRAVELLER');
        });
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('SAFARI_OPERATOR');
        });
    }
}
