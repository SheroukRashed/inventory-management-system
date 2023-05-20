<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('conclusive_product_id')->nullable(false);
            $table->foreign('conclusive_product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('ingredients', function (Blueprint $table) {
                $table->dropForeign('conclusive_product_id');
            });
        }
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('conclusive_product_id');
        });
    }
};
