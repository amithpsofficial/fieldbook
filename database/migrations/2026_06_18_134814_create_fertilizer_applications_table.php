<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fertilizer_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('land_id')->nullable()->constrained()->nullOnDelete();
            $table->string('brand_name');
            $table->string('chemical_content')->nullable();
            $table->string('vendor_name')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('dosage_amount', 8, 2)->nullable();
            $table->enum('dosage_unit', ['gms_cent', 'ml_litre'])->nullable();
            $table->date('date_applied');
            $table->enum('climate', ['sunny', 'cloudy', 'slight_rainy', 'rainy'])->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fertilizer_applications');
    }
};