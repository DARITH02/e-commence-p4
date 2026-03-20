<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$products = DB::table('products')
    ->whereIn('sku', ['SKU-11', 'SKU-133'])
    ->get();

foreach ($products as $product) {
    echo "SKU: {$product->sku}\n";
    echo "Name: {$product->name}\n";
    echo "Brand ID: " . ($product->brand_id ?? 'NULL') . "\n";
    echo "Price: {$product->price}\n";
    echo "Sale Price: " . ($product->sale_price ?? 'NULL') . "\n";
    echo "-------------------\n";
}

if (isset($product->brand_id)) {
    $brands = DB::table('brands')->get();
    foreach ($brands as $brand) {
        echo "Brand: {$brand->id} - {$brand->name}\n";
    }
}
