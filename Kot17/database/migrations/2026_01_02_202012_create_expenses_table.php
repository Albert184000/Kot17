<?php
// database/migrations/2024_01_01_000006_create_expenses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // food, utility, maintenance, salary, other
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->text('description');
            $table->string('vendor_name')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('receipt_image')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};