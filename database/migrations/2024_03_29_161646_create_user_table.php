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
            Schema::create('user', function (Blueprint $table) {
                $table->id();
                $table->string('nif')->unique();
                $table->string('password');
                $table->enum('roles', ['admin', 'empleado']);
                // $table->unsignedBigInteger('empleado_id')->nullable();
                // $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('set null');
                $table->timestamps();
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
