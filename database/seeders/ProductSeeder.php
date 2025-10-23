<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users EXCEPT the admin user
        $users = User::where('email', '!=', 'admin@admin.com')->get();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Please run UserSeeder and CategorySeeder first!');
            return;
        }

        $products = [
            [
                'title' => 'iPhone 13 Pro Max',
                'description' => 'Excellent condition iPhone 13 Pro Max with 256GB storage. Includes original box, charger, and protective case. Battery health at 95%. No scratches or dents.',
                'price' => 899.99,
                'condition' => 'like_new',
                'location' => 'Rīga, Latvia',
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'category_name' => 'Electronics',
            ],
            [
                'title' => 'Nike Air Jordan 1 Retro',
                'description' => 'Classic Air Jordan 1 in Black and Red colorway. Size 10.5. Worn only twice, in pristine condition. Comes with original box and extra laces.',
                'price' => 250.00,
                'condition' => 'like_new',
                'location' => 'Liepāja, Latvia',
                'latitude' => 56.5046,
                'longitude' => 21.0144,
                'category_name' => 'Fashion',
            ],
            [
                'title' => 'MacBook Pro 16" M2',
                'description' => '2023 MacBook Pro with M2 Max chip, 32GB RAM, 1TB SSD. Perfect for professionals. Barely used, like new condition. AppleCare+ included until 2025.',
                'price' => 2499.99,
                'condition' => 'like_new',
                'location' => 'Rīga, Latvia',
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'category_name' => 'Electronics',
            ],
            [
                'title' => 'Leather Sofa - Modern Design',
                'description' => 'Beautiful modern leather sofa in brown. 3-seater, very comfortable. Excellent condition, no stains or tears. Perfect for living room or office.',
                'price' => 450.00,
                'condition' => 'used',
                'location' => 'Jelgava, Latvia',
                'latitude' => 56.6509,
                'longitude' => 23.7134,
                'category_name' => 'Home & Garden',
            ],
            [
                'title' => '2019 Honda Civic',
                'description' => 'Well-maintained Honda Civic with 45,000 miles. Regular oil changes, new tires. Clean title, no accidents. Great fuel economy and reliable transportation.',
                'price' => 18500.00,
                'condition' => 'used',
                'location' => 'Jūrmala, Latvia',
                'latitude' => 56.9486,
                'longitude' => 23.6181,
                'category_name' => 'Vehicles',
            ],
            [
                'title' => 'Sony PlayStation 5',
                'description' => 'Brand new PS5 console with disc drive. Factory sealed, never opened. Includes DualSense controller and all cables. Perfect gift!',
                'price' => 499.99,
                'condition' => 'new',
                'location' => 'Rīga, Latvia',
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'category_name' => 'Electronics',
            ],
            [
                'title' => 'Designer Leather Jacket',
                'description' => 'Genuine leather jacket from premium brand. Size Medium. Classic black color, barely worn. Originally $800, selling for much less.',
                'price' => 299.00,
                'condition' => 'like_new',
                'location' => 'Ventspils, Latvia',
                'latitude' => 57.3947,
                'longitude' => 21.5644,
                'category_name' => 'Fashion',
            ],
            [
                'title' => 'Canon EOS R6 Camera',
                'description' => 'Professional mirrorless camera with 20MP full-frame sensor. Includes 24-105mm lens, battery grip, and 3 extra batteries. Perfect for photography enthusiasts.',
                'price' => 2199.00,
                'condition' => 'like_new',
                'location' => 'Rīga, Latvia',
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'category_name' => 'Electronics',
            ],
            [
                'title' => 'Harry Potter Complete Book Set',
                'description' => 'Complete Harry Potter series in hardcover. All 7 books in excellent condition. Perfect for collectors or first-time readers.',
                'price' => 120.00,
                'condition' => 'used',
                'location' => 'Rēzekne, Latvia',
                'latitude' => 56.5104,
                'longitude' => 27.3318,
                'category_name' => 'Books & Media',
            ],
            [
                'title' => 'Mountain Bike - Trek X-Caliber',
                'description' => 'Trek X-Caliber 8 mountain bike with 29" wheels. Shimano components, hydraulic disc brakes. Recently serviced, rides like new.',
                'price' => 850.00,
                'condition' => 'used',
                'location' => 'Daugavpils, Latvia',
                'latitude' => 55.8747,
                'longitude' => 26.5362,
                'category_name' => 'Sports & Outdoors',
            ],
            [
                'title' => 'Dining Table Set with 6 Chairs',
                'description' => 'Solid wood dining table with 6 matching chairs. Modern farmhouse style. Table extends to seat 8. Minor wear consistent with normal use.',
                'price' => 650.00,
                'condition' => 'used',
                'location' => 'Valmiera, Latvia',
                'latitude' => 57.5383,
                'longitude' => 25.4264,
                'category_name' => 'Home & Garden',
            ],
            [
                'title' => 'Apple Watch Series 8',
                'description' => 'Apple Watch Series 8, 45mm, Starlight Aluminum. GPS + Cellular. Includes original band and charging cable. Perfect condition.',
                'price' => 349.99,
                'condition' => 'like_new',
                'location' => 'Rīga, Latvia',
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'category_name' => 'Electronics',
            ],
            [
                'title' => 'Lego Star Wars Millennium Falcon',
                'description' => 'Complete Lego Millennium Falcon set (75192). All pieces included, built once and displayed. Comes with original box and instructions.',
                'price' => 650.00,
                'condition' => 'like_new',
                'location' => 'Liepāja, Latvia',
                'latitude' => 56.5046,
                'longitude' => 21.0144,
                'category_name' => 'Toys & Games',
            ],
            [
                'title' => 'KitchenAid Stand Mixer',
                'description' => 'Professional 6-quart KitchenAid mixer in red. Includes multiple attachments. Used occasionally, works perfectly. Great for baking enthusiasts.',
                'price' => 280.00,
                'condition' => 'like_new',
                'location' => 'Jelgava, Latvia',
                'latitude' => 56.6509,
                'longitude' => 23.7134,
                'category_name' => 'Home & Garden',
            ],
            [
                'title' => 'Ray-Ban Aviator Sunglasses',
                'description' => 'Classic Ray-Ban Aviators with gold frame and green lenses. Comes with original case and cleaning cloth. Barely worn.',
                'price' => 120.00,
                'condition' => 'like_new',
                'location' => 'Jūrmala, Latvia',
                'latitude' => 56.9486,
                'longitude' => 23.6181,
                'category_name' => 'Fashion',
            ],
            [
                'title' => 'Schwinn Exercise Bike',
                'description' => 'Schwinn IC4 indoor cycling bike with Bluetooth connectivity. Adjustable seat and handlebars. Like new condition, barely used.',
                'price' => 450.00,
                'condition' => 'like_new',
                'location' => 'Rīga, Latvia',
                'latitude' => 56.9496,
                'longitude' => 24.1052,
                'category_name' => 'Sports & Outdoors',
            ],
            [
                'title' => 'Weber Gas Grill',
                'description' => 'Weber Genesis II 3-burner gas grill. Stainless steel construction. Recently cleaned and maintained. Perfect for summer BBQs.',
                'price' => 550.00,
                'condition' => 'used',
                'location' => 'Ventspils, Latvia',
                'latitude' => 57.3947,
                'longitude' => 21.5644,
                'category_name' => 'Home & Garden',
            ],
            [
                'title' => 'Nintendo Switch OLED',
                'description' => 'Nintendo Switch OLED model with neon Joy-Cons. Includes dock, controllers, and 3 games (Mario Kart, Zelda, Animal Crossing).',
                'price' => 380.00,
                'condition' => 'like_new',
                'location' => 'Rēzekne, Latvia',
                'latitude' => 56.5104,
                'longitude' => 27.3318,
                'category_name' => 'Electronics',
            ],
            [
                'title' => 'Acoustic Guitar - Yamaha FG800',
                'description' => 'Yamaha FG800 acoustic guitar in natural finish. Excellent beginner or intermediate guitar. Includes hard case and tuner.',
                'price' => 180.00,
                'condition' => 'used',
                'location' => 'Daugavpils, Latvia',
                'latitude' => 55.8747,
                'longitude' => 26.5362,
                'category_name' => 'Books & Media',
            ],
            [
                'title' => 'Dyson V11 Vacuum Cleaner',
                'description' => 'Dyson V11 cordless vacuum with multiple attachments. Powerful suction, long battery life. Excellent condition, works perfectly.',
                'price' => 380.00,
                'condition' => 'like_new',
                'location' => 'Valmiera, Latvia',
                'latitude' => 57.5383,
                'longitude' => 25.4264,
                'category_name' => 'Home & Garden',
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->where('name', $productData['category_name'])->first() 
                     ?? $categories->random();
            
            $user = $users->random();

            Product::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => $productData['title'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'condition' => $productData['condition'],
                'status' => 'available',
                'location' => $productData['location'],
                'latitude' => $productData['latitude'],
                'longitude' => $productData['longitude'],
                'is_active' => true,
                'view_count' => rand(0, 100),
                'images' => [],
            ]);
        }

        $this->command->info('Products seeded successfully! (Admin account excluded)');
        $this->command->info('Total products created: ' . count($products));
        $this->command->info('Products assigned to ' . $users->count() . ' non-admin user(s)');
    }
}