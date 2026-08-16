<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crop_stocks', function (Blueprint $table) {
            $table->foreignId('land_id')->nullable()->after('crop_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('crop_stocks', function (Blueprint $table) {
            $table->dropForeign(['land_id']);
            $table->dropColumn('land_id');
        });
    }
};