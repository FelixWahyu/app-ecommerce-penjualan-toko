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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_pemesanan');
            $table->string('id_metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->enum('status', ['baru', 'proses', 'dikirim', 'sukses', 'canceled'])->default('baru');
            $table->string('currency')->nullable();
            $table->string('metode_pengiriman')->nullable();
            $table->decimal('ongkos_kirim', 10, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('grand_total', 10, 2)->nullable();
            // $table->string('phone')->nullable();
            // $table->longText('alamat_pengiriman')->nullable();
            // $table->string('provinsi')->nullable();
            // $table->string('kota')->nullable();
            // $table->string('kode_pos')->nullable();
            // $table->string('resi')->nullable();
            // $table->string('ekpedisi')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
