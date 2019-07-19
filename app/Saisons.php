<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Saisons extends Model
{
    use RecordsActivity;
    protected $fillable = ['name', 'numero', 'type', 'serie_id', 'nosaison', 'slug', 'publication', 'userId'];

    public function setUserIdAttribute($value){
        return $value;
    }
    public function getUserIdAttribute(){
        return Auth::user()->id;
    }
    public function serie(){
        return $this->belongsTo('App\Serie');
    }
    public function episodes(){
        return $this->hasMany('App\Episodes');
    }
}
