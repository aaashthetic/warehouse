<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_logs', function (Blueprint $table) {
            $table->id('log_id'); // Auto-increment PK
            $table->string('sku'); // Foreign key
            $table->string('location');
            $table->timestamp('last_scanned')->useCurrent(); // Auto timestamp
            $table->enum('status', ['IN', 'OUT']); // Only IN/OUT allowed

            // FK constraint
            $table->foreign('sku')
                  ->references('sku')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_logs');
    }
};