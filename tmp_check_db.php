<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $hasColumn = Schema::hasColumn('products', 'brand_id');
    echo "Has brand_id: " . ($hasColumn ? 'YES' : 'NO') . "\n";
    
    if ($hasColumn) {
        $count = DB::table('products')->whereNotNull('brand_id')->count();
        echo "Products with brand_id: " . $count . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
