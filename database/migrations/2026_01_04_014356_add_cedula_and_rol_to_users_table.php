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
        Schema::table('users', function (Blueprint $table) {
            // Añadimos cédula única (para evitar duplicados)
            $table->string('cedula', 10)->unique()->nullable()->after('id');

            // Añadimos idrol para conectar con tu tabla de ROLES
            $table->integer('idrol')->default(2)->after('cedula'); // 2 = Empleado por defecto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
