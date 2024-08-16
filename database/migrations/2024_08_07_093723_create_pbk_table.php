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
        Schema::create('pbk', function (Blueprint $table) {
            $table->id();
            $table->char('buk', 30);
            $table->char('npo', 30)->comment('Nomor Inv bukti beli');
            $table->date('tgl')->comment('Tanggal transaksi');
            $table->date('tjt')->comment('Tanggal jatuh tempo');
            $table->foreignId('ar_id')->constrained('ar')->comment('Id customers supplier');
            $table->decimal('jrp', 15, 2)->comment('Total pembelian');
            $table->string('ket', 255)->comment('Keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pbk');
    }
};
