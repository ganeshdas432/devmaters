<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function PHPSTORM_META\type;

class CancellationReasonSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('cancellation_reasons')->insert(
            [
                [
                    'reason' => 'The customer was unable to pay for the order',
                    'is_active' => true,
                    'type' => 'Rider',
                ],
                [
                    'reason' => 'The shipping costs were unexpected or high',
                    'is_active' => true,
                    'type' => 'Customer',
                ],
                [
                    'reason' => 'The customer requested to cancel the order',
                    'is_active' => true,
                    'type' => 'Admin'
                ],

            ]
        );
    }
}
