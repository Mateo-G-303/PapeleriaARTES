<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'idrol';
    public $timestamps = false;

    protected $fillable = [
        'nombrerol',
        'descripcionrol',
        'estadorol'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'idrol', 'idrol');
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'idrol', 'idrol');
    }
}