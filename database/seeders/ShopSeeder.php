<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;

class ShopSeeder extends Seeder
{
    public function run()
    {
        // Create a vendor user first
        $vendor = User::create([
            'name' => 'Sample Vendor',
            'email' => 'vendor@demo.com',
            'password' => bcrypt('1234'),
            'role' => 'vendor',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create sample shops
        $shops = [
            [
                'shop_name' => 'Food Corner',
                'vendor_id' => $vendor->id,
                'address' => '123 Food Street, Deoghar, Jharkhand',
                'mobile' => '9876543210',
                'latitude' => 24.4833,
                'longitude' => 86.7167,
                'shop_type' => 'food',
                'status' => 'active',
                'opening_time' => '09:00:00',
                'closing_time' => '22:00:00',
                'minimum_order' => 100,
                'delivery_charge' => 30,
                'delivery_radius' => 5,
                'is_featured' => true,
                'description' => 'Best food delivery restaurant in town',
                'commission_rate' => 10,
            ],
            [
                'shop_name' => 'Fresh Grocery',
                'vendor_id' => $vendor->id,
                'address' => '456 Market Road, Deoghar, Jharkhand',
                'mobile' => '9876543211',
                'latitude' => 24.4850,
                'longitude' => 86.7200,
                'shop_type' => 'grocery',
                'status' => 'active',
                'opening_time' => '08:00:00',
                'closing_time' => '21:00:00',
                'minimum_order' => 200,
                'delivery_charge' => 40,
                'delivery_radius' => 4,
                'is_featured' => true,
                'description' => 'Fresh groceries delivered to your doorstep',
                'commission_rate' => 8,
            ],
            [
                'shop_name' => 'City Pharmacy',
                'vendor_id' => $vendor->id,
                'address' => '789 Health Avenue, Deoghar, Jharkhand',
                'mobile' => '9876543212',
                'latitude' => 24.4820,
                'longitude' => 86.7150,
                'shop_type' => 'pharmacy',
                'status' => 'active',
                'opening_time' => '07:00:00',
                'closing_time' => '23:00:00',
                'minimum_order' => 100,
                'delivery_charge' => 20,
                'delivery_radius' => 6,
                'is_featured' => true,
                'description' => '24/7 medicine delivery service',
                'commission_rate' => 5,
            ]
        ];

        foreach ($shops as $shop) {
            Shop::create($shop);
        }
    }
}
