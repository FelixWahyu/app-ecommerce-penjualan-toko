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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('id_produk')->constrained('produks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kode_produk')->unique();
            $table->integer('quantity')->default(1);
            $table->decimal('jumlah_satuan', 10, 2)->nullable();
            $table->decimal('jumlah_total', 10, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
