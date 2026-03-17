<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Banner;
use App\Models\Setting;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\ShippingAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        // 2. Create Products, Images & Variants
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

            // Product Images
            ProductImage::firstOrCreate([
                'product_id' => $product->id,
                'image_path' => 'demo/image-' . rand(1, 5) . '.jpg',
                'is_primary' => true
            ]);

            // Attach 1-2 random categories
            $product->categories()->syncWithoutDetaching($allCategories->random(rand(1, 2))->pluck('id'));
            
            // Variants (Colors, Sizes)
            $variant = \App\Models\ProductVariant::firstOrCreate([
                'product_id' => $product->id,
                'name' => 'Size'
            ]);
            
            \App\Models\ProductVariantValue::firstOrCreate([
                'variant_id' => $variant->id,
                'value' => 'Medium'
            ]);
        }

        $allProducts = Product::all();

        // 3. Create Orders, Users, Payments & Related User Data
        $users = User::factory(5)->create();
        
        foreach ($users as $user) {
            // Shipping Address
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

            // Wishlist
            Wishlist::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $allProducts->random()->id
            ]);

            // Review
            Review::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $allProducts->random()->id,
                'rating' => rand(3, 5),
                'comment' => 'Great product!'
            ]);

            // Cart
            $cart = Cart::create(['user_id' => $user->id]);
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $allProducts->random()->id,
                'quantity' => 1
            ]);

            // Activity Log
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'registered',
                'description' => 'User registered successfully',
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
                'ip_address' => '127.0.0.1'
            ]);

            // Orders
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

                // Payment
                if ($paymentStatus == 'paid') {
                    Payment::create([
                        'order_id' => $order->id,
                        'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                        'payment_method' => 'credit_card',
                        'amount' => 100, // Updated later
                        'status' => 'completed'
                    ]);
                }

                // Add 1-3 items to order
                $total = 0;
                $products = $allProducts->random(rand(1, 3));
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
                
                if ($paymentStatus == 'paid') {
                    $order->payments()->update(['amount' => $total]);
                }
            }
        }

        // 4. Create Banners
        Banner::firstOrCreate([
            'title' => 'Summer Sale',
            'image' => 'banners/summer.jpg',
            'link' => '/sale',
            'order' => 1,
            'is_active' => true
        ]);

        // 5. Create Settings
        Setting::firstOrCreate(
            ['key' => 'site_name'],
            ['value' => 'Ecommerce Pro', 'group' => 'general']
        );
        Setting::firstOrCreate(
            ['key' => 'currency'],
            ['value' => 'USD', 'group' => 'general']
        );
    }
}
