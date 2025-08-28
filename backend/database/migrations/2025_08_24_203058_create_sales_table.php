<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sale_id'); // Auto-increment PK
            $table->string('sku'); // FK
            $table->enum('month', [
                'January','February','March','April','May','June',
                'July','August','September','October','November','December'
            ]);
            $table->integer('sales');
            $table->decimal('predicted_demand', 10, 2)->nullable();

            // FK constraint
            $table->foreign('sku')
                  ->references('sku')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};