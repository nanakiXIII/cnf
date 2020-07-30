<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Events\userEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjetsCollection;
use App\Http\Resources\ProjetsResource;
use App\Http\Resources\serieOnlyResource;
use App\Repository\AccountRepository;
use App\Saisons;
use App\Serie;
use App\User;
use function GuzzleHttp\Promise\is_fulfilled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;
use Carbon\Carbon;


class SerieController extends Controller {

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(Request $request)
    {
        $projet= Serie::where('publication', 1)->orderBy('etat')->orderBy('titre')->get();
        return serieOnlyResource::collection($projet);
        //return new ProjetsCollection($projet);
    }
    public function show(Request $request)
    {
        $projet= Serie::where('publication', 1)->where('type', $request->type)->where('slug', $request->slug)->firstOrFail();
        return new ProjetsResource($projet);
    }
    public function telechargement(Request $request){
        $serie = Serie::find($request->serie_id);
        $episode = Episodes::find($request->episode_id);
        if ($serie){
            if ($request->user('api')){
                $user = $request->user('api')->id;
                $ip = 0;
                $check = true;
            }else{
                $user = 0;
                $ip = $request->ip();
                $check = false;
            }
            if ($check){
                $dowload = Downloads::where('user_id', $request->user('api')->id)
                    ->where("serie_id", $request->serie_id)
                    ->where('episode_id', $request->episode_id)
                    ->where('qualite', $request->qualiter)
                    ->first();
                if (!$dowload){
                    $dowload = Downloads::create([
                        'episode_id' => $request->episode_id,
                        'user_id' => $user,
                        'serie_id' => $request->serie_id,
                        'qualite' => $request->qualiter,
                        'time' => 0,
                        'ip_address' => $ip
                    ]);
                }
                else{
                    if ($request->qualiter == 'vue'){
                        $dowload->time = $request->duration;
                        $dowload->save();
                    }

                }
            }else{
                $dowload = Downloads::where('ip_address', $request->ip())
                    ->where("serie_id", $request->serie_id)
                    ->where('episode_id', $request->episode_id)
                    ->where('qualite', $request->qualiter)
                    ->first();
                if (!$dowload){
                    $dowload = Downloads::create([
                        'episode_id' => $request->episode_id,
                        'user_id' => $user,
                        'serie_id' => $request->serie_id,
                        'qualite' => $request->qualiter,
                        'time' => 0,
                        'ip_address' => $ip
                    ]);
                }
            }
            if(!empty($request->user('api')->id)){
                $username = $request->user('api')->name;
                $avatar = env('APP_URL').$request->user('api')->avatar;
            }else{
                $username = 'Anonyme';
                $avatar = 'https://vpnfacile.net/wp-content/uploads/2019/03/anonyme.png';
            }
            if($request->qualiter == 'dvd'){
                $filename = $episode->dvd;
                $qualiter = 'DVD';
                $etat = 'Téléchargement';
            }elseif($request->qualiter == 'hd'){
                $filename= $episode->hd;
                $qualiter = '720P';
                $etat = 'Téléchargement';
            }elseif($request->qualiter == 'fhd'){
             $filename= $episode->fhd;
             $qualiter = '1080P';
             $etat = 'Téléchargement';
         }else{
                $etat = 'Streaming';
                $filename= $episode->hd;
                $qualiter = ' ';
            }
            if(!$request->qualiter != 'vue' || !$dowload){
            $array = ["embed" =>[
                                    'title' => $etat.' '.$qualiter,
                                    'description'=>$serie->titre." $episode->type $episode->numero",
                                    'thumbnail' => ['url' => env('APP_URL').'/storage/images/images/'.$serie->image],
                                    'footer' => ['text' => $username, 'icon_url' => $avatar],
                                    'timestamp' => Carbon::now()->subHours(2)->toDateTimeString(),
                                    ]
                                ];
                       $channel = app(Discord::class)->send(env('Log'), $array );
            }


        }


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