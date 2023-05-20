<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    const PRODUCT_1_QTY = 150;
    const PRODUCT_2_QTY = 30;
    const PRODUCT_3_QTY = 20;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::create([
            'conclusive_product_id' => 4,
            'ingredient_id' => 1,
            'quantity' => self::PRODUCT_1_QTY,
        ]);

        Ingredient::create([
            'conclusive_product_id' => 4,
            'ingredient_id' => 2,
            'quantity' => self::PRODUCT_2_QTY,
        ]);

        Ingredient::create([
            'conclusive_product_id' => 4,
            'ingredient_id' => 3,
            'quantity' => self::PRODUCT_3_QTY,
        ]);
    }
}
