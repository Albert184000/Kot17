<?php
// database/migrations/2024_01_01_000004_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']);
            $table->string('category'); // collection, donation, expense_food, expense_utility, etc.
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->foreignId('created_by')->constrained('users');
            $table->string('reference_type')->nullable(); // DailyCollection, Donation, Expense
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();

            $table->index(['type', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};