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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('investor_id')->nullable();
            $table->unsignedBigInteger('opportunity_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('stripe_id', 255)->nullable();
            $table->foreign('business_id') 
                ->references('id')
                ->on('users');
            $table->foreign('investor_id') 
                ->references('id') 
                ->on('users');
            $table->foreign('opportunity_id') 
                ->references('id') 
                ->on('investment_opportunities');
            $table->foreign('bank_id') 
                ->references('id') 
                ->on('banks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
