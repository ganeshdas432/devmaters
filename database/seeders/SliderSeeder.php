<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    public function run()
    {
        $sliders = [
            [
                'url' => '/food-delivery',
                'shop_type' => 'food',
                'title' => 'Food Delivery',
                'description' => 'Order food from your favorite restaurants',
                'image_path' => 'sliders/food-delivery.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'url' => '/grocery-delivery',
                'shop_type' => 'grocery',
                'title' => 'Grocery Delivery',
                'description' => 'Get groceries delivered to your doorstep',
                'image_path' => 'sliders/grocery-delivery.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'url' => '/medicine-delivery',
                'shop_type' => 'pharmacy',
                'title' => 'Medicine Delivery',
                'description' => 'Get medicines delivered from nearby pharmacies',
                'image_path' => 'sliders/medicine-delivery.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ]
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
