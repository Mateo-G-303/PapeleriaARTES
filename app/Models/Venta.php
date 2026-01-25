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
        'idcli',
        'fechaven',
        'ivaven',
        'subtotalven',
        'totalven'
    ];

    protected $casts = [
        'fechaven' => 'datetime',
        'ivaven' => 'decimal:2',
        'subtotalven' => 'decimal:2',
        'totalven' => 'decimal:2'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idcli', 'idcli');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'idven', 'idven');
    }

    public function calcularTotales()
    {
        $this->subtotalven = $this->detalles->sum('subtotaldven');
        $ivaValor = $this->subtotalven * ($this->ivaven / 100);
        $this->totalven = $this->subtotalven + $ivaValor;
        $this->save();
    }
}