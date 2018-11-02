<?php
namespace App\Repository;

use App\Episodes;
use App\Serie;
use App\User;
use Illuminate\Support\Facades\DB;

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

    public function getListeAbonnement(user $user){
        $reponse = new class{};
        $arraySerie = [];
        $arrayEpisode= [];
        $arrayHistorique = [];
        $serie = $user->series()->select('serie_id AS id', 'titre', 'slug', 'image', 'type', 'synopsis')
            ->where('publication', true)
            ->orderBy('titre', 'ASC')->get();
        $historique = Episodes::join('downloads','episodes.id','episode_id')
            ->join('series', 'series.id', 'episodes.serie_id')
            ->join('saisons', 'saisons.id', 'episodes.saisons_id')
            ->where('downloads.user_id',$user->id)
            ->where('episodes.publication', true)
            ->orderBy('downloads.updated_at', 'DESC')
            ->select('episodes.image', 'downloads.qualite', 'series.titre', 'episodes.name', 'episodes.numero', 'series.type','series.slug', 'downloads.qualite','episodes.id as episode_id', 'downloads.updated_at', 'episodes.type', 'saisons.id as saison_id', 'series.type as serie_type', 'series.slug as serie_slug', 'saisons.type as saison_type', 'saisons.numero as saison_numero')
            ;
        foreach ($serie as $s){
            $s->typeFichier = $s->episodes()->select(DB::raw('count(*) as serie_count, type'))->where('publication', true)->groupBy('type')->get();
            foreach ($s->typeFichier as $f){
                $vue = Episodes::join('downloads','episodes.id','episode_id')
                    ->where('downloads.user_id',$user->id)
                    ->Where('downloads.serie_id', $s->id)
                    ->where('episodes.type', $f->type )
                    ->get();
                $f->historique = count($vue);
                $f->full = false;
                if ($f->historique == $f->serie_count){
                    $f->full = true;
                }
                $arrayHistorique[] = $f;
            }
            $s->typeFichier = $arrayHistorique;
            foreach ($s->episodes()->orderBy('type', 'ASC')->orderBy('saisons_id', 'DESC')->orderBy('numero', 'DESC')->get() as $e){
                if (in_array($e->id, $historique->pluck('episode_id')->toArray() )){
                    $e->verif = true;
                }else{
                    $e->verif = false;
                }
                $arrayEpisode[] = $e;
            }
            $s->fichier = $arrayEpisode;
            $s->visible = false;
            $arraySerie[]= $s;
        }
        $reponse->abonnement = $arraySerie;
        $reponse->historique = $historique->get();
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