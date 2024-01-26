<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    //
    protected $fillable = [
        'codigo',
        'precio',
        'porcentaje',
        'fecha_final',
        'estado',
        'tipo_promocion',
        'nombre',

    ];
 //Relationships Muchos a Muchos  agregado el 26 de 01 de 2024
    public function articulos() {
    	return $this->belongsToMany(Articulo::class,'promociones_articulos');
    }
   
}
