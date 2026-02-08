<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('idprov');
            $table->string('rucprov', 13)->unique();
            $table->string('nombreprov', 100);
            $table->string('correoprov', 100)->nullable();
            $table->string('telefonoprov', 15)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};