<?php
namespace App\Repository;

use App\Serie;
use App\User;

class AccountRepository{
    /**
     * @var User
     */
    private $user;

    /**
     * AccountRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAbonnement(user $user){

        return $user->series()->select('serie_id AS id', 'titre', 'slug', 'image', 'type', 'synopsis')
            ->where('publication', true)
            ->orderBy('titre', 'ASC')->get();

    }

    public function getAbonnements(user $user, string $type){
        $abo = $user->series()->select('serie_id AS id', 'titre', 'slug', 'image')
            ->where('publication', true)
            ->orderBy('titre', 'ASC')->pluck('id')->toArray();
        $serie = Serie::where('publication', true)->where('type', $type)->get();
        $tab = [];
        foreach ($serie as $s){

            if (in_array($s->id, $abo )){
                $s->genres = $s->genres()->get();
                $s->abo = true;
                $tab[] = $s;
            }else{
                $s->genres = $s->genres()->get();
                $s->abo = false;
                $tab[] = $s;
            }
        }
        return $tab;
    }



}