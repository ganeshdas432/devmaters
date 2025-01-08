<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'mobile' => Str::random(10),
            'age' => rand(18, 65),
            'gender' => 'male',
            'role' => 'admin',
            'status' => true,
            'password' => bcrypt('12345678'),
            'profile_photo' => null,
            'latitude' => null,
            'longitude' => null,
            'address' => null,
            'device_token' => null,
            'last_active_at' => now(),
            'is_online' => false,
            'preferences' => json_encode([]),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}