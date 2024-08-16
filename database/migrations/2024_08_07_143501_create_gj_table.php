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
        Schema::create('gj', function (Blueprint $table) {
            $table->id();
            $table->char('buk', 30);
            $table->date('tgl')->comment('Tanggal transaksi');
            $table->foreignId('gl_id')->constrained('gl')->comment('Id gl');
            $table->char('jdk', 1);
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
        Schema::dropIfExists('gj');
    }
};
