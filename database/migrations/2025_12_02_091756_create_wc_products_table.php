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
        Schema::create('wc_products', function (Blueprint $table) {
            $table->id();
            $table->string('wc_id')->unique();
            $table->enum('type', ['simple', 'variable']);
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('published')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('visibility')->default('visible');
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('in_stock')->default(true);
            $table->integer('stock')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('categories')->nullable();
            $table->string('tags')->nullable();
            $table->string('shipping_class')->nullable();
            $table->string('url')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wc_products');
    }
};
