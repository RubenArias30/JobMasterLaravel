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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->integer('telephone');
            $table->string('country');
            $table->string('photo')->nullable();


            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('address')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
