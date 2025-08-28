<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id('alert_id'); // Auto-increment PK
            $table->string('sku');  // Foreign key to products
            $table->integer('stock');
            $table->string('alert');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraint
            $table->foreign('sku')
                  ->references('sku')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_alerts');
    }
};
