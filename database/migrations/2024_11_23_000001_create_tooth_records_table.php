<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tooth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->integer('tooth_number');
            $table->enum('status', ['normal', 'decayed', 'filled', 'missing', 'crown', 'bridge'])->default('normal');
            $table->timestamps();
            // Composite unique index to prevent duplicate tooth records for a patient
            $table->unique(['patient_id', 'tooth_number']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('tooth_records');
    }
};
