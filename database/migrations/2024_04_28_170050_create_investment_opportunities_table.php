<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investment_opportunities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid()->default(DB::raw('(UUID())'))->index();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('business_name');
            $table->text('description');
            $table->integer('amount_needed');
            $table->integer('remaining_amount')->nullable();
            $table->string('potential_risks');
            $table->string('future_growth');
            $table->string('products_or_services');
            $table->string('returns_percentage');
            $table->string('company_valuation');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('revenues');
            $table->string('net_profit');
            $table->string('profit_margin');
            $table->string('cash_flow');
            $table->string('ROI');
            $table->string('photo');
            $table->decimal('commission_amount', 20, 5)->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->foreign('category_id') 
                ->references('id') 
                ->on('categories'); 
            $table->foreign('business_id') 
                ->references('id') 
                ->on('users')
                ->onDelete('cascade'); 
            $table->foreign('admin_id') 
                ->references('id') 
                ->on('users')
                ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_opportunities');
    }
};
