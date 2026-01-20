<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'idven';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'fechaven',
        'subtotalven',
        'ivaven',
        'totalven'
    ];

    protected $casts = [
        'fechaven' => 'date',
        'subtotalven' => 'decimal:2',
        'ivaven' => 'decimal:2',
        'totalven' => 'decimal:2'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'idven', 'idven');
    }

    // Calcular el valor del IVA
    public function getIvaValorAttribute()
    {
        return $this->subtotalven * ($this->ivaven / 100);
    }
}