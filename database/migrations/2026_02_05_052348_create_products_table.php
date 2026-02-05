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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('address');
            $table->text('desc');
            $table->integer('promo_price')->nullable();
            $table->integer('price');
            $table->enum('unit', ['hari', 'bulan', 'tahun']);
            $table->float('admin_fee');
            $table->enum('status', ['penuh', 'tersedia']);
            $table->integer('qty')->default(1);
            $table->boolean('is_verify');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
