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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->foreignId('id_category')->constrained('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('harga_jual', 10, 2);
            $table->foreignId('id_unit')->constrained('units')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('stock');
            $table->longText('deskripsi');
            $table->string('image_produk')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
