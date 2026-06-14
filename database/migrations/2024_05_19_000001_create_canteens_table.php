<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canteens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ibu_kantin_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->decimal('balance', 12, 2)->default(0);
            $table->boolean('is_open')->default(true);
            $table->timestamps();

            $table->foreign('ibu_kantin_id')->references('id')->on('users')->onDelete('set null');
        });

        // Tambahkan foreign key canteen_id ke users setelah tabel canteens ada
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('canteen_id')->references('id')->on('canteens')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['canteen_id']);
        });
        Schema::dropIfExists('canteens');
    }
};
