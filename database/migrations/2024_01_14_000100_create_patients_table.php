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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fb_name')->nullable();
            $table->string('gender');
            $table->date('next_visit')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->boolean('has_hmo')->default(false);
            $table->string('hmo_company')->nullable();
            $table->string('hmo_number')->nullable();
            $table->string('hmo_type')->nullable();
            $table->enum('patient_type', ['Walk-in', 'Orthodontics', 'Insurance'])->default('Walk-in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
