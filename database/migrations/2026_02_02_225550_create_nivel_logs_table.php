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
        Schema::create('nivellogs', function (Blueprint $table) {
            $table->id('idnivel'); // Llave primaria personalizada
            $table->string('nombrenivel', 50);
            $table->text('descripcionnivel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nivel_logs');
    }
};
