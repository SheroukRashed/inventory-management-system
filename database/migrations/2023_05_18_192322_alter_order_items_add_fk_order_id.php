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
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
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
                $table->dropForeign('order_id');
            });
        }
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
};
