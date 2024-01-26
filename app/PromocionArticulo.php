<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromocionArticulo extends Model
{
    //
    protected $fillable = [
        'idpromociones',
        'idarticulos',
        'estado',
    ];
}
