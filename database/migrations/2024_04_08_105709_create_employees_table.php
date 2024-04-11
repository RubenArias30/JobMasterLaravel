<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->string('telephone');
            $table->string('country');
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('users_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

                $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');

                $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

        //     $table->foreignId('users_id')->nullable()->constrained('users')->onDelete('cascade');
        //     $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('cascade');
        //    $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            $table->timestamps();

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
