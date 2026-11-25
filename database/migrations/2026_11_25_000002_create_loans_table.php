<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->integer('duration_days')->default(7);
            $table->date('pickup_date');
            $table->enum('collateral_type', ['KTP', 'KTM', 'SIM', 'Kartu Pelajar']);
            $table->enum('status', ['pending_pickup', 'active', 'returned', 'overdue', 'cancelled'])->default('pending_pickup');
            $table->timestamp('borrowed_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->integer('fine_amount')->default(0);
            $table->boolean('is_fine_paid')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
