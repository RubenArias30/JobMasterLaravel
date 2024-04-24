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
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->float('price');
            $table->integer('quantity');
            $table->integer('concept_discount');
            $table->integer('concept_iva');
            $table->integer('concept_irpf');

            $table->foreignId('invoices_id')->constrained('invoices')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};
