<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('user-id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kk');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price');
            $table->enum('status', ['cicilan', 'cash']);
            $table->enum('confirmation', ['proses', 'berhasil']);
            $table->enum('payment_status', ['proses', 'lunas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
