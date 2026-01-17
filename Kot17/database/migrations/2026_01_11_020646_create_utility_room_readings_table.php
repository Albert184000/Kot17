<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utility_room_readings', function (Blueprint $table) {
            $table->id();

            // ✅ only FK that MUST exist
            $table->foreignId('utility_bill_id')
                ->constrained('utility_bills')
                ->cascadeOnDelete();

            // ✅ DO NOT FK to rooms now (avoid error 150)
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('room_name')->nullable();  // user input

            $table->string('meter_no')->nullable();

            $table->decimal('old_reading', 12, 2)->default(0);
            $table->decimal('new_reading', 12, 2)->default(0);
            $table->decimal('usage_units', 12, 2)->default(0);

            $table->decimal('common_share_units', 12, 2)->default(0);
            $table->decimal('total_units', 12, 2)->default(0);

            $table->decimal('amount_before_donation', 14, 2)->default(0);
            $table->decimal('donation_share', 14, 2)->default(0);
            $table->decimal('amount_final', 14, 2)->default(0);

            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('balance_amount', 14, 2)->default(0);

            $table->string('status')->nullable(); // ok, pending
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utility_room_readings');
    }
};
