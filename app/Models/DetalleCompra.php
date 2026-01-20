<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detallecompras';
    protected $primaryKey = 'iddetc';
    public $timestamps = false;

    protected $fillable = [
        'idcom',
        'idpro',
        'cantidaddetc',
        'preciocompra'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idpro', 'idpro');
    }
}