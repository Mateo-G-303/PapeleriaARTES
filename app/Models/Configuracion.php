<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'idconfig';
    public $timestamps = false;

    protected $fillable = [
        'clave',
        'valor',
        'descripcion'
    ];

    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    public static function obtenerIva()
    {
        return (float) self::obtener('iva_porcentaje', 12.00);
    }

    public static function actualizarIva($valor)
    {
        return self::updateOrCreate(
            ['clave' => 'iva_porcentaje'],
            ['valor' => $valor, 'descripcion' => 'Porcentaje de IVA aplicado a ventas']
        );
    }
}
