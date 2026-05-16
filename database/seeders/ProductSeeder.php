<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get a user to act as seller (create one if doesn't exist)
        $seller = User::where('role', 'seller')->first() ?? User::factory()->create(['role' => 'seller', 'name' => 'Snap Seller']);

        $products = [
            // Clothes
            ['name' => 'Vintage Denim Jacket', 'category' => 'Clothes', 'price' => 45.00, 'description' => 'Classic blue denim jacket in great condition.'],
            ['name' => 'Summer Floral Dress', 'category' => 'Clothes', 'price' => 30.00, 'description' => 'Lightweight floral dress, perfect for sunny days.'],
            
            // Electronics
            ['name' => 'Noise Cancelling Headphones', 'category' => 'Electronics', 'price' => 120.00, 'description' => 'Barely used Sony WH-1000XM4.'],
            ['name' => 'Mechanical Keyboard', 'category' => 'Electronics', 'price' => 75.00, 'description' => 'RGB mechanical keyboard with blue switches.'],
            
            // Vehicles
            ['name' => 'Mountain Bike', 'category' => 'Vehicles', 'price' => 250.00, 'description' => '21-speed mountain bike, needs minor brake adjustment.'],
            ['name' => 'Electric Scooter', 'category' => 'Vehicles', 'price' => 350.00, 'description' => 'Foldable electric scooter with 20km range.'],
            
            // Toys
            ['name' => 'LEGO Star Wars Set', 'category' => 'Toys', 'price' => 60.00, 'description' => 'Millennium Falcon LEGO set, complete with box.'],
            ['name' => 'Remote Control Car', 'category' => 'Toys', 'price' => 40.00, 'description' => 'High-speed RC car, comes with extra batteries.'],
            
            // Furniture
            ['name' => 'Minimalist Coffee Table', 'category' => 'Furniture', 'price' => 85.00, 'description' => 'Modern oak wood coffee table.'],
            ['name' => 'Ergonomic Office Chair', 'category' => 'Furniture', 'price' => 150.00, 'description' => 'Adjustable lumbar support chair, very comfortable.'],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, ['seller_id' => $seller->id]));
        }
    }
}
