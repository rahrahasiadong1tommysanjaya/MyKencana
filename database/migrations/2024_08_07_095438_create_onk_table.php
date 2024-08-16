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
        Schema::create('onk', function (Blueprint $table) {
            $table->id();
            $table->char('buk', 35);
            $table->date('tgl');
            $table->foreignId('ok_id')->constrained('ok');
            $table->char('ket', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onk');
    }
};
