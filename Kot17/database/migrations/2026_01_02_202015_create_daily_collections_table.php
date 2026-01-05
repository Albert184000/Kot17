<?php
// database/migrations/2024_01_01_000003_create_daily_collections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('collected_by')->constrained('users');
            $table->date('collection_date');
            $table->decimal('amount', 10, 2)->default(5000.00);
            $table->enum('status', ['collected', 'pending', 'waived'])->default('collected');
            $table->time('collected_at_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint: one collection per member per day
            $table->unique(['member_id', 'collection_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_collections');
    }
};