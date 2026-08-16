<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labour_expense_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('labour_expense_id')->constrained()->cascadeOnDelete();
            $table->foreignId('labourer_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labour_expense_details');
    }
};