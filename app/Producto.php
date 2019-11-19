<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Bodega;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'cantidad',
        'estado',
        'observaciones',
        'bodega_id',
    ];


    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

}
