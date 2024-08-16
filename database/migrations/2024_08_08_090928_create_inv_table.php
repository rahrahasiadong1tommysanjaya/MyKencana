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
        Schema::create('inv', function (Blueprint $table) {
            $table->id();
            $table->char('acc', 5)->comment('code');
            $table->char('ket', 100)->comment('produk');
            $table->decimal('harga', 15, 2);
            $table->foreignId('sat_id')->constrained('sat');
            $table->foreignId('jp_id')->constrained('jp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv');
    }
};
