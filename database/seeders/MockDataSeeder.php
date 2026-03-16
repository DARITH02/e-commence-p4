<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\ShippingAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Health & Beauty', 'slug' => 'health-beauty'],
            ['name' => 'Shoes', 'slug' => 'shoes'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $allCategories = Category::all();

        // 2. Create Products
        for ($i = 1; $i <= 20; $i++) {
            $name = "Product $i";
            $product = Product::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "This is a detailed description for $name. It's high quality and affordable.",
                    'price' => rand(20, 500),
                    'sku' => "SKU-$i",
                    'stock_status' => 'instock',
                    'is_active' => true,
                    'is_featured' => rand(0, 1),
                ]
            );

            // Attach 1-2 random categories
            $product->categories()->sync($allCategories->random(rand(1, 2))->pluck('id'));
        }

        // 3. Create Orders (Realistic for dashboard stats)
        $users = User::factory(5)->create();
        
        foreach ($users as $user) {
            // Create a shipping address for the user
            $address = ShippingAddress::create([
                'user_id' => $user->id,
                'first_name' => $user->name,
                'last_name' => 'Demo',
                'email' => $user->email,
                'phone' => '0123456789',
                'address_line1' => '123 Test St',
                'city' => 'Phnom Penh',
                'state' => 'PP',
                'postal_code' => '12000',
                'country' => 'Cambodia',
            ]);

            // Create 2 orders per user
            for ($o = 1; $o <= 2; $o++) {
                $status = ['pending', 'processing', 'completed', 'shipped'][rand(0, 3)];
                $paymentStatus = ($status == 'completed' || $status == 'shipped') ? 'paid' : 'pending';
                
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => $status,
                    'total_amount' => 0,
                    'payment_method' => 'Credit Card',
                    'payment_status' => $paymentStatus,
                    'shipping_address_id' => $address->id,
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);

                // Add 1-3 items to order
                $total = 0;
                $products = Product::inRandomOrder()->limit(rand(1, 3))->get();
                foreach ($products as $p) {
                    $qty = rand(1, 2);
                    $price = $p->price;
                    $itemTotal = $qty * $price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $p->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'total_price' => $itemTotal,
                    ]);
                    $total += $itemTotal;
                }
                $order->update(['total_amount' => $total]);
            }
        }
    }
}
