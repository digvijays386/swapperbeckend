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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Example: Product name
            $table->text('description')->nullable(); // Example: Product description
            $table->decimal('price', 10, 2)->nullable(); // Example: Price (adjust precision as needed)
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->unsignedBigInteger('category_id')->nullable(); // Foreign key to categories table (can be nullable)

            // Add other product-specific columns as needed (e.g., 'condition', 'size', etc.)
            $table->timestamps();

            // Foreign key constraints (after defining the columns)
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('product_categories'); // Make sure 'categories' table exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};