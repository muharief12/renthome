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
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id')->constrained('rents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('code');
            $table->float('price');
            $table->enum('status', ['dp', 'cicilan', 'pelunasan']);
            $table->enum('confirmation', ['proses', 'berhasil']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
