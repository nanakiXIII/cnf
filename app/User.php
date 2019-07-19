<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function series(){
        return $this->belongsToMany('App\Serie');
    }
    public function competence(){
        return $this->belongsToMany('App\postes');
    }
    public function download(){
        return $this->hasMany('App\Downloads', 'user_id');
    }
    /**
     * Get the activity timeline for the user.
     *
     * @return mixed
     */
    public function activity()
    {
        return $this->hasMany('App\Activity')
            ->with(['user', 'subject'])
            ->latest();
    }
    /**
     * Record new activity for the user.
     *
     * @param  string $name
     * @param  mixed  $related
     * @throws \Exception
     * @return void
     */
    public function recordActivity($name, $related)
    {
        if (! method_exists($related, 'recordActivity')) {
            throw new \Exception('..');
        }
        return $related->recordActivity($name);
    }
}
