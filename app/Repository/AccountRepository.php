<?php
namespace App\Repository;

use App\Episodes;
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
        $reponse = new class{};
        $reponse->abonnement = $user->series()->select('serie_id AS id', 'titre', 'slug', 'image', 'type', 'synopsis')
            ->where('publication', true)
            ->orderBy('titre', 'ASC')->get();
        $reponse->historique = Episodes::join('downloads','episodes.id','episode_id')
            ->join('series', 'series.id', 'episodes.serie_id')
            ->join('saisons', 'saisons.id', 'episodes.saisons_id')
            ->where('downloads.user_id',$user->id)
            ->where('episodes.publication', true)
            ->orderBy('downloads.updated_at', 'DESC')
            ->select('episodes.image', 'downloads.qualite', 'series.titre', 'episodes.name', 'episodes.numero', 'series.type','series.slug', 'downloads.qualite','episodes.id as episode_id', 'downloads.updated_at', 'episodes.type', 'saisons.id as saison_id', 'series.type as serie_type', 'series.slug as serie_slug', 'saisons.type as saison_type', 'saisons.numero as saison_numero')
            ->get();
        return json_encode($reponse);



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