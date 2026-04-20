<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('complainant_name', 150);
            $table->string('respondent_name', 150);
            $table->string('incident_type', 100);
            $table->date('date');
            $table->string('location', 255);
            $table->text('description');
            $table->enum('status', ['pending', 'under_investigation', 'resolved', 'dismissed'])->default('pending');
            $table->string('complainant_email', 150)->nullable();
            $table->string('respondent_email', 150)->nullable();
            $table->dateTime('hearing_date')->nullable();
            $table->text('hearing_notes')->nullable();
            $table->unsignedBigInteger('recorded_by');
            $table->timestamps();

            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
