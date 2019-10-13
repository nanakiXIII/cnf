<?php

namespace App;

use BeyondCode\Comments\Traits\HasComments;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasComments;
    protected $fillable = ['titre', 'contenu', 'publication','staff', 'publish_at','image', 'type','etat','categorie_id', 'serie_id','user_id','staff_id','user_id', 'slug'];
    protected $dates = [
        'created_at',
        'updated_at',
        'publish_at'
    ];
    public function needsCommentApproval($model): bool
    {
        return false;
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function fichiers(){
        return $this->belongsToMany('App\Episodes');
    }
}
