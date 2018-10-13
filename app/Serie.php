<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['titre', 'titre_original', 'titre_alternatif', 'annee', 'studio', 'auteur', 'episode', 'oav', 'films', 'bonus', 'scan', 'light-novel', 'visual-novel', 'synopsis', 'staff', 'type', 'publication', 'slug', 'image', 'coprod'];

    public function users(){
        return $this->belongsToMany('App\User');
    }
}
