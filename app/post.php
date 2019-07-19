<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = ['titre', 'contenu', 'publication','staff', 'publish_at','image', 'type','etat','categorie_id', 'serie_id','user_id','staff_id','user_id', 'slug'];
    protected $dates = [
        'created_at',
        'updated_at',
        'publish_at'
    ];
    public function fichiers(){
        return $this->belongsToMany('App\Episodes');
    }
}
