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

    protected $casts = [
        'estadorol' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'idrol', 'idrol');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'idrol', 'idper')
                    ->withTimestamps();
    }

    public function tienePermiso($nombrePermiso)
    {
        return $this->permisos()->where('nombreper', $nombrePermiso)->exists();
    }

  public function getPermisosIds()
{
    return $this->permisos()->pluck('permisos.idper')->toArray();
}

    public function sincronizarPermisos(array $permisosIds)
    {
        $this->permisos()->sync($permisosIds);
    }
}