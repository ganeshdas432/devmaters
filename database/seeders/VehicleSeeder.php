<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vehicles')->insert([
            [
                'name' => 'Cycle',
                'delivery_area' => '1',
            ],
            [
                'name' => 'Bike',
                'delivery_area' => '10',
            ],
            [
                'name' => 'Car',
                'delivery_area' => '100',
            ]
        ]);
    }
}