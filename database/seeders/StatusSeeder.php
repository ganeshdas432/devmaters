<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            [
                'title' => 'Active',
                'color' => '#28a745',
                'icon' => 'check-circle',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Inactive',
                'color' => '#dc3545',
                'icon' => 'times-circle',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Pending',
                'color' => '#ffc107',
                'icon' => 'clock',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'title' => 'Blocked',
                'color' => '#6c757d',
                'icon' => 'ban',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'title' => 'Verified',
                'color' => '#17a2b8',
                'icon' => 'check-double',
                'is_active' => true,
                'sort_order' => 5
            ],

        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
