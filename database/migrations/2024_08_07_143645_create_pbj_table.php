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
        Schema::create('pbj', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pbk_id')->constrained('pbk');
            $table->foreignId('ofk_id')->constrained('ofk');
            $table->decimal('hst', 15, 2);
            $table->decimal('qti', 15, 2);
            $table->decimal('jrp', 15, 2);
            $table->string('ket', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pbj');
    }
};
