<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crop_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crop_id')->constrained()->cascadeOnDelete();
            $table->string('buyer_name');
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('weight_sold_kg', 10, 2);
            $table->decimal('total_income', 10, 2);
            $table->boolean('deduct_from_stock')->default(true);
            $table->date('sale_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_sales');
    }
};