<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditoria';      // Tu nombre de tabla real
    protected $primaryKey = 'idaud';     // Tu llave primaria personalizada
    public $timestamps = false;          // Desactivamos los timestamps automáticos porque tú usas 'fechahoraaud'

    protected $fillable = ['user_id', 'accionaud', 'tablaaud', 'fechahoraaud'];

    // Relación para saber el nombre del usuario, no solo su ID
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
