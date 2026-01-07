<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin','treasurer','utility','collector','member')
            NOT NULL DEFAULT 'member'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin','treasurer','utilities_treasurer','collector','member')
            NOT NULL DEFAULT 'member'
        ");
    }
};
