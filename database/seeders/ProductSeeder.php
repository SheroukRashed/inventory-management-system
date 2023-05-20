<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Beef',
            'type_id' => Product::TYPE_INGREDIENT,
        ]);

        Product::create([
            'name' => 'Cheese',
            'type_id' => Product::TYPE_INGREDIENT,
        ]);

        Product::create([
            'name' => 'Onion',
            'type_id' => Product::TYPE_INGREDIENT,
        ]);

        Product::create([
            'name' => 'Burger',
        ]);
    }
}
