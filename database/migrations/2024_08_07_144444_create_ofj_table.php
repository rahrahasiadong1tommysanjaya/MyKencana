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
        Schema::create('ofj', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ofk_id')->constrained('ofk');
            $table->foreignId('onj_id')->constrained('onj');
            $table->decimal('jrp', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofj');
    }
};
