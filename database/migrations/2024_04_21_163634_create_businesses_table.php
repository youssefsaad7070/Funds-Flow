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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('phone')->unique()->nullable();
            $table->text('description');
            $table->string('tax_card_number')->unique();
            $table->string('current_address')->nullable();
            $table->string('nationality')->nullable();
            $table->decimal('total_returns', 20, 2)->nullable();
            $table->decimal('total_funds_raised', 20, 2)->nullable();
            $table->timestamps();
            $table->foreign('user_id') // Define the foreign key constraint
                  ->references('id') // References the 'id' field in the 'users' table
                  ->on('users') // Table to reference
                  ->onDelete('cascade'); // Optional: Action when user is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
