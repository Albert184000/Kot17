<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Convert old stored values to canonical (if any)
        DB::statement("UPDATE users SET monk_rank='bhikkhu'  WHERE monk_rank IN ('senior_monk')");
        DB::statement("UPDATE users SET monk_rank='samanera' WHERE monk_rank IN ('junior_monk','monk','novice','samaner','samanera ')");

        // 2) Convert Khmer stored values (if you ever stored Khmer directly)
        DB::statement("UPDATE users SET monk_rank='maha_thera' WHERE monk_rank IN ('ព្រះមហាថេរ','មហាថេរ')");
        DB::statement("UPDATE users SET monk_rank='bhikkhu'    WHERE monk_rank IN ('ព្រះភិក្ខុ','ភិក្ខុ')");
        DB::statement("UPDATE users SET monk_rank='samanera'   WHERE monk_rank IN ('ព្រះសាមណេរ','សាមណេរ')");

        // 3) Now alter ENUM to canonical ONLY (MySQL)
        DB::statement("ALTER TABLE users MODIFY monk_rank ENUM('maha_thera','bhikkhu','samanera') NULL");
    }

    public function down(): void
    {
        // If you want, revert to string to be safe (recommended down)
        DB::statement("ALTER TABLE users MODIFY monk_rank VARCHAR(50) NULL");
    }
};
