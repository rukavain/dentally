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
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity');
            $table->integer('minimum_quantity');
            $table->string('serial_number');
            $table->decimal('cost_per_item', 10, 2);
            $table->decimal('total_value', 10, 2);
            $table->enum('availability', ['available', 'out-of-stock', 'to-order']);
            $table->string('notes')->nullable();
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
