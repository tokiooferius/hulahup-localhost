<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('transaction_code')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('canteen_id')->constrained('canteens')->onDelete('cascade');
            $table->decimal('amount_for_canteen', 15, 2);
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('canteen_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('canteen_id')->constrained('canteens')->onDelete('cascade');
            $table->foreignId('payment_detail_id')->nullable()->constrained('payment_details')->onDelete('set null');
            $table->decimal('amount_received', 15, 2);
            $table->enum('status', ['pending', 'completed', 'withdrawn'])->default('pending');
            $table->dateTime('settlement_date')->nullable();
            $table->string('bank_account')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canteen_settlements');
        Schema::dropIfExists('payment_details');
        Schema::dropIfExists('payments');
    }
};
