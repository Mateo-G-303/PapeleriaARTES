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
        'totalven'
    ];

    protected $casts = [
        'fechaven' => 'date',
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
}