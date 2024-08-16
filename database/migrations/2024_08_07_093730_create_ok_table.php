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
        Schema::create('ok', function (Blueprint $table) {
            $table->id();
            $table->char('buk', 35);
            $table->date('tgl');
            $table->date('tjt');
            $table->foreignId('ar_id')->constrained('ar');
            $table->char('jnt', 5);
            $table->decimal('jrp', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ok');
    }
};
