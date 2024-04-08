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
            $table->id('employees_id');
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->number('telephone');
            $table->string('country');
            $table->string('photo')->nullable();

            // $table->foreign('address_id')->references('address_id')->on('addresses')->onDelete('cascade');
            // $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
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
