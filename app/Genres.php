<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genres extends Model
{
    protected $fillable = ['name'];

    public function series(){
        return $this->belongsToMany('App\Serie');
    }
}
