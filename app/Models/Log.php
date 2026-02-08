<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'idlogs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'idnivel',
        'mensajelogs',
        'fechalogs'
    ];

    // Relación: El log pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: El log tiene un nivel de gravedad
    public function nivel()
    {
        return $this->belongsTo(NivelLog::class, 'idnivel', 'idnivel');
    }
}
