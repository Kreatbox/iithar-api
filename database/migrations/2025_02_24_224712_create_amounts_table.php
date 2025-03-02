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
        Schema::create('amounts', function (Blueprint $table) {
            $table->id(); 
            $table->enum('blood_type', ['A+', 'A-', 'AB+', 'AB-', 'B+', 'B-', 'O+', 'O-']);  
            $table->integer('quantity');  
            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade'); 
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amounts');
    }
};
