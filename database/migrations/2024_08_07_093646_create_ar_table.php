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
        Schema::create('ar', function (Blueprint $table) {
            $table->id();
            $table->char('acc', 5)->comment('code');
            $table->char('ket', 100)->comment('nama supplier');
            $table->longText('alm')->comment('alamat');
            $table->integer('kelurahan_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ar');
    }
};
