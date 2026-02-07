<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelLog extends Model
{
    // 1. Le decimos el nombre exacto de la tabla
    protected $table = 'nivellogs';

    // 2. Definimos la llave primaria personalizada
    protected $primaryKey = 'idnivel';

    // 3. Como no usamos "created_at" y "updated_at", lo desactivamos
    public $timestamps = false;

    protected $fillable = ['nombrenivel', 'descripcionnivel'];

    // Relación: Un nivel tiene muchos logs
    public function logs()
    {
        return $this->hasMany(Log::class, 'idnivel', 'idnivel');
    }
}
