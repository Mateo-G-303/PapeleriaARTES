<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 50)->unique();
            $table->string('valor', 255);
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
        });

        // Insertar IVA por defecto
        DB::table('configuracion')->insert([
            'clave' => 'iva_porcentaje',
            'valor' => '12.00',
            'descripcion' => 'Porcentaje de IVA aplicado a las ventas',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};