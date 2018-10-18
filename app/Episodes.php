<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episodes extends Model
{
    protected $fillable = ['name', 'numero', 'type', 'dvd', 'hd','fhd','image', 'serie','publication', 'etat', 'streaming'];

    public function saison(){
        return $this->belongsTo('App\Saisons');
    }

    public function serie(){
        return $this->belongsTo('App\Serie');
    }
}
