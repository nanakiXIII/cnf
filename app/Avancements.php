<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Avancements extends Model
{
    use RecordsActivity;
    protected $fillable = ['episode', 'serie_id', 'saison_id', 'encodage', 'adapt', 'edition', 'qcheck', 'traduction', 'time', 'check', 'type', 'user_id', 'publication'];

    public function setUserIdAttribute($value){
        return $value;
    }
    public function getUserIdAttribute(){
        return Auth::user()->id;
    }

    public function users(){
        return $this->belongsTo('App\User');
    }
    public function saisons(){
        return $this->belongsTo('App\Saisons', 'saison_id');
    }
    public function serie(){
        return $this->belongsTo('App\Serie');
    }
}
