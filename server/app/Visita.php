<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{

	protected $table = 'visitas';

    protected $fillable = [
        'lugar_id', 'estado', 'texto', 'respuesta', 'lat', 'lng', 'llamadas', 'referencia'
    ];

    public function lugar()
    {
        return $this->belongsTo('App\Lugar');
    }
}
