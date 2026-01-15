<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idpro';
    public $timestamps = false;

    protected $fillable = [
        'idcat',
        'codbarraspro',
        'nombrepro',
        'preciominpro',
        'preciomaxpro',
        'stockpro',
        'estadocatpro',
        'preciocomprapro',
        'precioventapro',
        'stockminpro'
    ];

    protected $casts = [
        'preciominpro' => 'decimal:2',
        'preciomaxpro' => 'decimal:2',
        'preciocomprapro' => 'decimal:2',
        'precioventapro' => 'decimal:2',
        'estadocatpro' => 'boolean'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idcat', 'idcat');
    }

    public function tieneStockDisponible($cantidad = 1)
    {
        return ($this->stockpro - $cantidad) >= $this->stockminpro;
    }

    public function stockDisponibleParaVenta()
    {
        return $this->stockpro - $this->stockminpro;
    }
}