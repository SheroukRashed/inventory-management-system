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
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('conclusive_item_id')->nullable();
            $table->foreign('conclusive_item_id')
                    ->references('id')
                    ->on('order_items')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign('conclusive_item_id');
            });
        }
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('conclusive_item_id');
        });
    }
};
