<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episodes extends Model
{
    protected $fillable = ['name', 'numero', 'type', 'dvd', 'hd','fhd','image', 'serie_id','saisons_id','publication', 'etat', 'streaming'];

    public function saison(){
        return $this->belongsTo('App\Saisons', 'saisons_id');
    }

    public function serie(){
        return $this->belongsTo('App\Serie');
    }
    public function news(){
        return $this->belongsToMany('App\post');
    }
    public function download(){
        return $this->hasMany('App\Downloads', 'episode_id');
    }
}
