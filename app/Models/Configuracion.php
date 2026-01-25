<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = ['clave', 'valor', 'descripcion'];

    public static function obtenerIva()
    {
        $config = self::where('clave', 'iva_porcentaje')->first();
        return $config ? floatval($config->valor) : 12.00;
    }

    public static function actualizarIva($valor)
    {
        return self::updateOrCreate(
            ['clave' => 'iva_porcentaje'],
            [
                'valor' => $valor,
                'descripcion' => 'Porcentaje de IVA aplicado a las ventas'
            ]
        );
    }
}