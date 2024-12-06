<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tooth_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tooth_record_id')->constrained('tooth_records')->onDelete('cascade');
            $table->text('note_text');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('tooth_notes');
    }
};
