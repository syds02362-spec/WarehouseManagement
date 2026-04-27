<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$products = App\Models\Product::all();
$expired = $products->where('quantity', '<=', 0)->count();
$low_stock = $products->filter(function($product) {
    return $product->quantity > 0 && $product->quantity <= $product->min_quantity;
})->count();
$total_items = $products->count();
$total_quantity = $products->sum('quantity');
echo 'Expired: ' . $expired . PHP_EOL;
echo 'Low Stock: ' . $low_stock . PHP_EOL;
echo 'Total Items: ' . $total_items . PHP_EOL;
echo 'Total Quantity: ' . $total_quantity . PHP_EOL;
?>