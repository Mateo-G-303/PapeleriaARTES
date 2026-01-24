<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalleventas', function (Blueprint $table) {
            $table->id('iddven');
            $table->unsignedBigInteger('idven');
            $table->unsignedBigInteger('idpro');
            $table->integer('cantidaddven');
            $table->decimal('preciounitariodven', 10, 2);
            $table->decimal('subtotaldven', 10, 2);

            $table->foreign('idven')->references('idven')->on('ventas')->onDelete('cascade');
            $table->foreign('idpro')->references('idpro')->on('productos')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalleventas');
    }
};
