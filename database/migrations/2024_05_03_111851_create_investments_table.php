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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opportunity_id');
            $table->unsignedBigInteger('investor_id');
            $table->decimal('amount', 20, 5);
            $table->timestamps();
            $table->foreign('investor_id') // Define the foreign key constraint
                ->references('id') // References the 'id' field in the 'users' table
                ->on('users');
            $table->foreign('opportunity_id') // Define the foreign key constraint
                ->references('id') // References the 'id' field in the 'users' table
                ->on('investment_opportunities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
