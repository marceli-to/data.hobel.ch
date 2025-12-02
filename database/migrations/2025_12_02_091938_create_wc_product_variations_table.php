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
        Schema::create('wc_product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wc_product_id')->constrained('wc_products')->cascadeOnDelete();
            $table->string('wc_id')->unique();
            $table->string('parent_sku')->index();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('published')->default(true);
            $table->string('visibility')->default('visible');
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('in_stock')->default(true);
            $table->integer('stock')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->json('attribute_values')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wc_product_variations');
    }
};
