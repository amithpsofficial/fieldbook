<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Widen the entry_type enum to include 'weekly_total'.
        // Done via raw SQL because changing an enum column with
        // Schema::table()->change() requires doctrine/dbal, which
        // isn't installed in this project.
        DB::statement("ALTER TABLE labour_expenses MODIFY entry_type ENUM('daily_total', 'weekly_total', 'per_labourer') NOT NULL");

        Schema::table('labour_expenses', function (Blueprint $table) {
            $table->date('period_end_date')->nullable()->after('expense_date');
        });
    }

    public function down(): void
    {
        Schema::table('labour_expenses', function (Blueprint $table) {
            $table->dropColumn('period_end_date');
        });

        DB::statement("ALTER TABLE labour_expenses MODIFY entry_type ENUM('daily_total', 'per_labourer') NOT NULL");
    }
};