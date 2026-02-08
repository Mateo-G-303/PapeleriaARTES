<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // 1. Nombre exacto de tu tabla en Postgres
    protected $table = 'categorias';

    // 2. Tu llave primaria personalizada
    protected $primaryKey = 'idcat';

    // 3. Desactivamos timestamps (Laravel busca created_at por defecto, tú no los tienes)
    public $timestamps = false;

    protected $fillable = ['nombrecat', 'descripcioncat', 'activocat'];
}
