<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Genres extends Model
{
    protected $fillable = ['name', 'userId'];

    public function setUserIdAttribute($value){
        return $value;
    }
    public function getUserIdAttribute($value){

        return Auth::user()->id;
    }
    public function series(){
        return $this->belongsToMany('App\Serie');
    }
}
