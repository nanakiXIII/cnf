<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Http\Controllers\Controller;
use App\Repository\AccountRepository;
use App\Saisons;
use App\Serie;
use function GuzzleHttp\Promise\is_fulfilled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class SerieController extends Controller {

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function serieAboLog(Request $request, string $type){
        return $this->accountRepository->getAbonnements($request->user(), $type);

    }
    public function serieAbo(Request $request, string $type){
        $serie = Serie::where('publication', true)->where('type', $type)->get();
        $tab = [];
        foreach ($serie as $s){
            $s->genres = $s->genres;
            $s->abo = false;
            $tab[] = $s;
        }
        return $tab;
    }
    public function infoSerie(Request $request, string $type, string $slug){
        $serie = Serie::where('publication', true)->where('type', $type)->where('slug', $slug)->firstOrFail();
        if ($request->user()){
            if (in_array($serie->id, $request->user()->series()->pluck('serie_id')->toArray() )){
                $serie->abo = true;
            }else{
                $serie->abo = false;
            }
        }
        else{
            $serie->abo = false;
        }
        if ($serie->etat != 3){
            $epi = [];
            $saison = $serie->saisons()->where('publication', true)->orderBy('type', 'ASC')->orderBy('numero', 'DESC')->get();
            foreach($saison as $s){
                $episode = $s->episodes()->where('publication', true)->orderBy('type', 'ASC')->orderBy('numero', 'DESC')->get();
                $dow = [];
                foreach ($episode as $e){
                    if ($request->user()){
                        if($request->user()->download()->where('episode_id', $e->id)->where('qualite', 'dvd')->first()){
                            $e->downloaddvd = true;
                        }
                        if($request->user()->download()->where('episode_id', $e->id)->where('qualite', 'hd')->first()){
                            $e->downloadhd = true;
                        }
                        if($request->user()->download()->where('episode_id', $e->id)->where('qualite', 'fhd')->first()){
                            $e->downloadfhd = true;
                        }
                        if($request->user()->download()->where('episode_id', $e->id)->where('qualite', 'vue')->first()){
                            $e->vue = true;
                        }
                    }
                    $e->downloads = count(Downloads::where('episode_id', $e->id)->where('qualite', '!=', 'vue')->get());
                    $e->vues = count(Downloads::where('episode_id', $e->id)->where('qualite', '=', 'vue')->get());
                    $dow[] = $e;
                }
                $s->episodes = $dow;

                $epi[] = $s;
            }
            $serie->saisons = $epi;
        }

        $serie->suivis = count($serie->users()->get());
        $serie->genres = $serie->genres;
        return $serie;
    }
    public function infoEpisode(Request $request, string $type, string $slug, int $saison, int $episode){
        $serie = Serie::where('publication', true)->where('type', $type)->where('slug', $slug)->firstOrFail();
        $saison = Saisons::find($saison);
        $episode = Episodes::find($episode);
        if (isset($request->server()['HTTP_X_FORWARDED_FOR'])){
            $ip = $request->server()['HTTP_X_FORWARDED_FOR'];
        }
        else{
            $ip = $request->ip();
        }
        if ($request->user()){
            $verif = Downloads::where('episode_id', $episode->id)->where('qualite', 'vue')->where('user_id', $request->user()->id)->first();
        }else{
            $verif = Downloads::where('episode_id', $episode->id)->where('qualite', 'vue')->where('user_id', '0')->where('ip_address', $ip)->first();
        }
        if ($verif){
            $serie->verif = $verif;
        }else{
            $serie->verif = false;
        }
        $serie->getEpisode = $episode;
        $serie->getSaison = $saison;
        return $serie;
    }

}