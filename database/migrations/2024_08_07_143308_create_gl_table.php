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
        Schema::create('gl', function (Blueprint $table) {
            $table->id();
            $table->char('acc', 5)->comment('code');
            $table->char('ket', 100)->comment('jenis satuan');
            $table->foreignId('jgl_id')->constrained('jgl')->comment('Jenis gl neraca/lb');
            $table->foreignId('gg_id')->constrained('gg')->comment('group GL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gl');
    }
};
