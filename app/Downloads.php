<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downloads extends Model
{
    protected $fillable = ['episode_id', 'user_id', 'serie_id','qualite', 'time', 'ip_address'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function episode(){
        return $this->belongsTo('App\Episodes');
    }
}
