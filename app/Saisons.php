<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saisons extends Model
{
    protected $fillable = ['name', 'numero', 'type', 'image', 'serie', 'nosaison', 'slug', 'publication'];

    public function serie(){
        return $this->belongsTo('App\Serie');
    }
    public function episodes(){
        return $this->hasMany('App\Episodes');
    }
}
