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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->unique();
            $table->integer('stock_quantity')->default(0);
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('category_ids')->nullable(); // Store multiple category IDs
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('featured')->default(false);
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('attributes')->nullable(); // Size, Color, etc.
            $table->json('product_meta')->nullable(); // Store product meta data like size image
            $table->json('product_size')->nullable(); // Store multiple sizes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
