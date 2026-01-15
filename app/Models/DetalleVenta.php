<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalleventas';
    protected $primaryKey = 'iddven';
    public $timestamps = false;

    protected $fillable = [
        'idven',
        'idpro',
        'cantidaddven',
        'preciounitariodven',
        'subtotaldven'
    ];

    protected $casts = [
        'preciounitariodven' => 'decimal:2',
        'subtotaldven' => 'decimal:2'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'idven', 'idven');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idpro', 'idpro');
    }
}