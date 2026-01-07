<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Table already exists in DB, so skip creating it.
        if (Schema::hasTable('profile_update_requests')) {
            return;
        }

        // (Optional) if it didn't exist, create it
        Schema::create('profile_update_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('old_data');
            $table->json('new_data');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // ✅ Only drop if exists (safe)
        Schema::dropIfExists('profile_update_requests');
    }
};
