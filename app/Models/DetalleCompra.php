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
        'cantidaddet',
        'preciounitario',
        'costototalpaquete'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idcom', 'idcom');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idpro', 'idpro');
    }
}
