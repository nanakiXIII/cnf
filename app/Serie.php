<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['titre', 'titre_original', 'titre_alternatif', 'annee', 'studio', 'auteur', 'episode', 'oav', 'films', 'bonus', 'scan', 'ln', 'vn', 'synopsis', 'staff', 'type', 'publication', 'slug', 'image', 'coprod'];

    public function users(){
        return $this->belongsToMany('App\User');
    }
    public function genres(){
        return $this->belongsToMany('App\Genres');
    }
    public function saisons(){
        return $this->hasMany('App\Saisons');
    }
    public function episodes(){
        return $this->hasMany('App\Episodes');
    }
    public function download(){
        return $this->hasMany('App\Downloads', 'serie_id');
    }

}
