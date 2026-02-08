<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'idcom';
    public $timestamps = false;

    protected $fillable = [
        'idprov',
        'codigocom',
        'fechacom',
        'montototalcom'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idprov', 'idprov');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'idcom', 'idcom');
    }
    
}