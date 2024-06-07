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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('transaction_number');
            $table->timestamps();
        });
    }

    // CREATE TABLE Bank (
    //     Bank_ID INT PRIMARY KEY AUTO_INCREMENT,
    //     Name VARCHAR(255),
    //     Address VARCHAR(255),
    //     Transaction_Number VARCHAR(255)
    //   );
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
