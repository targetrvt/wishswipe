<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Phones, laptops, tablets, and other electronic devices',
                'icon' => 'fas fa-mobile-alt',
                'is_active' => true,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing, shoes, and accessories',
                'icon' => 'fas fa-tshirt',
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Furniture, decor, and gardening tools',
                'icon' => 'fas fa-home',
                'is_active' => true,
            ],
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'description' => 'Cars, motorcycles, bikes, and vehicle parts',
                'icon' => 'fas fa-car',
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, movies, music, and games',
                'icon' => 'fas fa-book',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment, fitness gear, and outdoor items',
                'icon' => 'fas fa-futbol',
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Children toys, board games, and collectibles',
                'icon' => 'fas fa-gamepad',
                'is_active' => true,
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => 'health-beauty',
                'description' => 'Cosmetics, skincare, and wellness products',
                'icon' => 'fas fa-heart',
                'is_active' => true,
            ],
            [
                'name' => 'Tools & Hardware',
                'slug' => 'tools-hardware',
                'description' => 'Power tools, hand tools, and hardware supplies',
                'icon' => 'fas fa-wrench',
                'is_active' => true,
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'description' => 'Pet food, toys, and accessories',
                'icon' => 'fas fa-paw',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}