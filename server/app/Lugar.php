<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{

	protected $table = 'lugares';

    protected $fillable = [
        'user_id', 'estado', 'nombre', 'slug', 'imagen', 'lat', 'lng'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

	public function visitas()
	{
		return $this->hasMany('App\Visita');
	}

}