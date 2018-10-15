<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\AccountRepository;
use App\Serie;
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
        $saison = $serie->saisons()->where('publication', true)->get();
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
            $serie->saisons = $saison;
        }

        $serie->suivis = count($serie->users()->get());
        $serie->genres = $serie->genres;
        return $serie;
    }

}