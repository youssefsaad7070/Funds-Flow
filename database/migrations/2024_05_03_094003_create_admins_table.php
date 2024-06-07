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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->decimal('total_commission', 20, 5)->nullable();
            $table->timestamps();
            $table->foreign('user_id') // Define the foreign key constraint
                ->references('id') // References the 'id' field in the 'users' table
                ->on('users')
                ->onDelete('cascade'); // Table to reference 
        });
    }
    // CREATE TABLE Platform_Admin (
    //     Admin_ID INT PRIMARY KEY AUTO_INCREMENT,
    //     Name VARCHAR(255),
    //     Email VARCHAR(255),
    //     Password VARCHAR(255),
    //     Commission DECIMAL(20 , 5),
    //     Business_ID INT,
    //     Investor_ID INT,
    //     Bank_ID INT
    //   );
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
