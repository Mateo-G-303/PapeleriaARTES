<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('subtotalven', 10, 2)->default(0)->after('fechaven');
            $table->decimal('ivaven', 5, 2)->default(12)->after('subtotalven');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['subtotalven', 'ivaven']);
        });
    }
};