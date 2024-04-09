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
        Schema::create('concept', function (Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('discount');
            $table->integer('concept_iva');
            $table->integer('concept_irpf');

            $table->foreignId('invoices_id')->constrained('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concept');
    }
};
