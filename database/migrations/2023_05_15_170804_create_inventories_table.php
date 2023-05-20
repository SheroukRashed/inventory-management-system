<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id')->nullable(false)->default(config('system')['default_stock_id']);
            $table->float('left_in_stock_quantity');
            $table->float('threshold_quantity');
            $table->tinyInteger('is_limited')->default(false);
            $table->timestamps();
            $table->softDeletes();    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
