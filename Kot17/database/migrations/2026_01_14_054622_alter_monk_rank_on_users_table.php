<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ✅ 1) Normalize old values (if you had old ranks)
        DB::statement("
            UPDATE users
            SET monk_rank = 'bhikkhu'
            WHERE monk_rank IN ('senior_monk', 'bhikkhu', 'ភិក្ខុ', 'ព្រះភិក្ខុ')
        ");

        DB::statement("
            UPDATE users
            SET monk_rank = 'samanera'
            WHERE monk_rank IN ('monk', 'junior_monk', 'novice', 'samanera', 'សាមណេរ', 'ព្រះសាមណេរ')
        ");

        DB::statement("
            UPDATE users
            SET monk_rank = 'maha_thera'
            WHERE monk_rank IN ('maha_thera', 'maha-thera', 'មហាថេរ', 'ព្រះមហាថេរ')
        ");

        // ✅ 2) Make monk_rank flexible (fix truncation forever)
        DB::statement("ALTER TABLE users MODIFY monk_rank VARCHAR(20) NULL");
    }

    public function down(): void
    {
        // keep it simple
        DB::statement("ALTER TABLE users MODIFY monk_rank VARCHAR(20) NULL");
    }
};
