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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Action performed
            $table->string('model_type'); // Model type (e.g., DentistSchedule)
            $table->unsignedBigInteger('model_id'); // ID of the model
            $table->unsignedBigInteger('user_id'); // ID of the user who performed the action
            $table->string('user_email');
            $table->text('changes')->nullable(); // Changes made (if applicable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
