<?php
// app/Models/Proveedor.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    public $timestamps = false;

    protected $table = 'proveedores';
    protected $primaryKey = 'idprov';

    protected $fillable = [
        'rucprov',
        'nombreprov',
        'correoprov',
        'telefonoprov',
        'direccionprov'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'idprov', 'idprov');
    }
}
