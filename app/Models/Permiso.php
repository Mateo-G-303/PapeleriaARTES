<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'idper';
    public $timestamps = false;

    protected $fillable = [
        'idrol',
        'nombreper',
        'descripcionper',
        'estadoper'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }
}