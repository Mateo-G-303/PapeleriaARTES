<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('idcli');
            $table->string('ruc_identificacion', 13)->unique();
            $table->string('nombre', 255);
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('es_consumidor_final')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};