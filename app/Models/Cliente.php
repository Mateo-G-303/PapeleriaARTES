<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idcli';

    protected $fillable = [
        'ruc_identificacion',
        'nombre',
        'direccion',
        'telefono',
        'email',
        'es_consumidor_final'
    ];

    protected $casts = [
        'es_consumidor_final' => 'boolean',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'idcli', 'idcli');
    }

    public static function consumidorFinal()
    {
        return self::firstOrCreate(
            ['ruc_identificacion' => '9999999999999'],
            [
                'nombre' => 'CONSUMIDOR FINAL',
                'es_consumidor_final' => true
            ]
        );
    }
}