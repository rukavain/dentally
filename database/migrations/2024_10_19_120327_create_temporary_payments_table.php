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
        Schema::create('temporary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade');
            $table->decimal('paid_amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->text('remarks')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('status')->default('pending'); // Status to indicate it's under review
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_payments');
    }
};
