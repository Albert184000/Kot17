<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('utility_bills', function (Blueprint $table) {
      $table->id();
      $table->string('type'); // electricity | water
      $table->string('month'); // YYYY-MM
      $table->decimal('price_per_unit', 12, 2)->default(0);
      $table->decimal('common_units', 12, 2)->default(0);
      $table->decimal('donation_amount', 14, 2)->default(0);

      $table->string('common_mode')->default('usage'); // usage|equal
      $table->text('note')->nullable();

      $table->decimal('sum_usage_units', 12, 2)->default(0);
      $table->decimal('sum_units_with_common', 12, 2)->default(0);
      $table->decimal('sum_amount_before_donation', 14, 2)->default(0);
      $table->decimal('sum_amount_final', 14, 2)->default(0);

      $table->timestamps();

      $table->unique(['type', 'month']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('utility_bills');
  }
};
