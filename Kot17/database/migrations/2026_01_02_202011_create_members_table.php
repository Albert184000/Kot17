    <?php
    // database/migrations/2024_01_01_000002_create_members_table.php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
{
    Schema::create('members', function (Blueprint $table) {
        $table->id();
        // ទុកតែមួយជួរនេះបានហើយ
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        
        $table->string('member_code')->unique();
        $table->string('room_number')->nullable();
        $table->enum('status', ['active', 'inactive', 'left'])->default('active');
        $table->date('join_date');
        $table->date('leave_date')->nullable();
        $table->text('address')->nullable();
        $table->string('emergency_contact')->nullable();
        $table->decimal('daily_rate', 10, 2)->default(5000.00);

        // លុបជួរ user_id ដែលនៅខាងក្រោមនេះចេញ (ជួរដែលអ្នកធ្លាប់ Comment ថា "ត្រូវតែមានជួរនេះ")
        
        $table->timestamps();
    });
}

        public function down(): void
        {
            Schema::dropIfExists('members');
        }
    };