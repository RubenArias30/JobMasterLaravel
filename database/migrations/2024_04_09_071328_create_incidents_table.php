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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->enum('incident_type', ['Delay','Absence','password_change','Request','Complaint','Others']);
            $table->text('description');
            $table->string('date');
            $table->enum('status', ['completed', 'pending'])->default('pending');

            $table->foreignId('employees_id')->constrained('employees')->onDelete('cascade');

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
