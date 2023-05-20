<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    const PRODUCT_1_MAX_QTY = 20000;
    const PRODUCT_2_MAX_QTY = 5000;
    const PRODUCT_3_MAX_QTY = 1000;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inventory::create([
            'product_id' => 1,
            'left_in_stock_quantity' => self::PRODUCT_1_MAX_QTY,
            'threshold_quantity' => self::PRODUCT_1_MAX_QTY / 2,
        ]);

        Inventory::create([
            'product_id' => 2,
            'left_in_stock_quantity' => self::PRODUCT_2_MAX_QTY,
            'threshold_quantity' => self::PRODUCT_2_MAX_QTY / 2,
        ]);

        Inventory::create([
            'product_id' => 3,
            'left_in_stock_quantity' => self::PRODUCT_3_MAX_QTY,
            'threshold_quantity' => self::PRODUCT_3_MAX_QTY / 2,
        ]);
    }
}
