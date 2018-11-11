<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class postes extends Model
{
    protected $fillable = ['name', 'site'];

    public function user(){
        return $this->belongsToMany('App\User');
    }
    public $timestamps = false;
}
