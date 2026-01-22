<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'idper';
    public $timestamps = false;

    protected $fillable = [
        'nombreper',
        'descripcionper',
        'estadoper'
    ];

    protected $casts = [
        'estadoper' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_permiso', 'idper', 'idrol');
    }

    // Agrupar permisos por módulo para la vista
    public static function getAgrupados()
    {
        $permisos = self::where('estadoper', true)->get();
        $agrupados = [];

        foreach ($permisos as $permiso) {
            $modulo = explode('.', $permiso->nombreper)[0];
            $modulo = ucfirst($modulo);
            
            if (!isset($agrupados[$modulo])) {
                $agrupados[$modulo] = [];
            }
            $agrupados[$modulo][] = $permiso;
        }

        return $agrupados;
    }
}