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
        Schema::create('dentists', function (Blueprint $table) {
            $table->id();
            $table->string('dentist_first_name');
            $table->string('dentist_last_name');
            $table->date('dentist_birth_date');
            $table->string('dentist_email')->unique();
            $table->string('dentist_gender');
            $table->string('dentist_phone_number');
            $table->string('dentist_specialization');
            $table->string('password');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dentists');
    }
};
