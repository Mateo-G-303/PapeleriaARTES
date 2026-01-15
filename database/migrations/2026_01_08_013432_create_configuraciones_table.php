<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id('idconfig');
            $table->string('clave', 50)->unique();
            $table->string('valor', 255);
            $table->string('descripcion', 150)->nullable();
        });

        DB::table('configuraciones')->insert([
            ['clave' => 'session_lifetime', 'valor' => '120', 'descripcion' => 'Tiempo de sesión en minutos'],
            ['clave' => 'max_login_attempts', 'valor' => '5', 'descripcion' => 'Intentos máximos de login antes de bloqueo'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};