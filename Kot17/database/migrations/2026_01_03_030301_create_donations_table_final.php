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
    Schema::create('donations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null'); // ភ្ជាប់ទៅសមាជិក
        $table->string('donor_name')->nullable(); // សម្រាប់អ្នកក្រៅដែលមិនមែនជាសមាជិក
        $table->decimal('amount', 15, 2);
        $table->string('currency', 3)->default('USD'); // បន្ថែមចូលទីនេះតែម្តង
        $table->dateTime('donated_at');
        $table->text('note')->nullable();
        $table->foreignId('user_id')->constrained(); // Admin អ្នកកត់ត្រា
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations_table_final');
    }
};
