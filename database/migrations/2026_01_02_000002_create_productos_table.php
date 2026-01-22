<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('idpro');
            $table->unsignedBigInteger('idcat');
            $table->string('codbarraspro', 20)->nullable();
            $table->string('nombrepro', 100);
            $table->decimal('preciominpro', 10, 2)->nullable();
            $table->decimal('preciomaxpro', 10, 2)->nullable();
            $table->decimal('preciocomprapro', 10, 2)->default(0);
            $table->decimal('precioventapro', 10, 2)->default(0);
            $table->integer('stockpro')->default(0);
            $table->integer('stockminpro')->default(5);
            $table->boolean('estadocatpro')->default(true);

            $table->foreign('idcat')->references('idcat')->on('categorias')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
