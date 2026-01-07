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
    Schema::table('users', function (Blueprint $table) {
        $table->enum('person_type', ['monk','lay'])->default('lay')->after('role');
        $table->enum('monk_rank', ['maha_thera','senior_monk','monk'])->nullable()->after('person_type');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['person_type','monk_rank']);
    });
}

};
