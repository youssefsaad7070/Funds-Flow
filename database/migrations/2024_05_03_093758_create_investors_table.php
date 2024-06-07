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
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('national_id')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('age')->nullable();
            $table->string('about')->nullable();
            $table->string('gender');
            $table->string('nationality')->nullable();
            $table->string('total_return')->nullable();
            $table->string('maximum_drawdown')->nullable();
            $table->decimal('total_invested', 20, 2)->nullable();
            $table->timestamps();
            $table->foreign('user_id') // Define the foreign key constraint
                ->references('id') // References the 'id' field in the 'users' table
                ->on('users')
                ->onDelete('cascade'); // Table to reference
        }); 
    }
    // Maximum_Drawdown VARCHAR(255),
    // Total_Invested DECIMAL(20, 2) ,

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
