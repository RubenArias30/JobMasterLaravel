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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('subtotal');
            $table->integer('discount');
            $table->integer('invoice_iva');
            $table->integer('invoice_irpf');
            $table->integer('total');

            $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('client')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};