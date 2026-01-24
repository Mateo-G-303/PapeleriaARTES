<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('idrol');
            $table->string('nombrerol', 50);
            $table->string('descripcionrol', 150)->nullable();
            $table->boolean('estadorol')->default(true);
        });

        // Insertar roles por defecto
        DB::table('roles')->insert([
            ['idrol' => 1, 'nombrerol' => 'Administrador', 'descripcionrol' => 'Acceso total al sistema', 'estadorol' => true],
            ['idrol' => 2, 'nombrerol' => 'Empleado', 'descripcionrol' => 'Acceso a ventas e inventario', 'estadorol' => true],
            ['idrol' => 3, 'nombrerol' => 'Auditor', 'descripcionrol' => 'Solo lectura y reportes', 'estadorol' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
