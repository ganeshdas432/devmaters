<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'title' => 'About Us',
                'description' => 'Welcome to our delivery platform. We are committed to providing the best delivery experience for our customers.',
                'status' => 'active'
            ],
            [
                'title' => 'Privacy Policy',
                'description' => 'This Privacy Policy describes how we collect, use, and handle your personal information when you use our services.',
                'status' => 'active'
            ],
            [
                'title' => 'Terms & Conditions',
                'description' => 'By accessing or using our service, you agree to be bound by these terms and conditions.',
                'status' => 'active'
            ]
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
