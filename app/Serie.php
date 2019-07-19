<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Serie extends Model
{
    use RecordsActivity;
    protected $fillable = ['titre', 'titre_original', 'titre_alternatif', 'annee', 'studio', 'auteur', 'episode', 'oav', 'films', 'bonus', 'scan', 'ln', 'vn', 'synopsis', 'staff', 'type', 'publication', 'slug', 'image', 'coprod', 'etat', 'userId'];

    public function setUserIdAttribute($value){
        return $value;
    }
    public function getUserIdAttribute(){
        return Auth::user()->id;
    }
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
