<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'أرز مصري',
            'price' => '50',
            'description' => 'أرز ذهبي عالي الجودة',
            'quantity' => 100,
            'min_quantity' => 20,
        ]);

        \App\Models\Product::create([
            'name' => 'سكر أبيض',
            'price' => '30',
            'description' => 'سكر نقي',
            'quantity' => 5,
            'min_quantity' => 10,
        ]);

        \App\Models\Product::create([
            'name' => 'زيت زيتون',
            'price' => '80',
            'description' => 'زيت زيتون طبيعي',
            'quantity' => 0,
            'min_quantity' => 5,
        ]);

        \App\Models\Product::create([
            'name' => 'شاي أخضر',
            'price' => '40',
            'description' => 'شاي عضوي',
            'quantity' => 50,
            'min_quantity' => 15,
        ]);
    }
}
