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
    Schema::table('members', function (Blueprint $table) {
        $table->string('name')->after('member_code'); // ថែមឈ្មោះ
        $table->string('phone')->after('name');        // ថែមលេខទូរស័ព្ទ
    });
}

public function down(): void
{
    Schema::table('members', function (Blueprint $table) {
        $table->dropColumn(['name', 'phone']);
    });
}
};
