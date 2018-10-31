<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Http\Controllers\Controller;
use App\Repository\AccountRepository;
use App\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class CompteController extends Controller {

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(Request $request){
        return $this->accountRepository->getAbonnement($request->user());

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
    public function update(Request $request){
        $user = $request->user();

        if($request->action == "Abonnement"){
            $serie = Serie::find($request->serie_id);
            if (!$request->abo){
                $user->series()->attach($serie);
            }else{
                $user->series()->detach($serie);
            }
            return [$request->abo];
        }elseif ($request->action == "streaming"){
            $serie = Serie::find($request->serie_id);
            if (isset($request->server()['HTTP_X_FORWARDED_FOR'])){
                $ip = $request->server()['HTTP_X_FORWARDED_FOR'];
            }
            else{
                $ip = $request->ip();
            }
            if ($serie){
                $episode = Episodes::where('id', $request->episode_id)->firstOrFail();
                if ($episode){
                    if ($user){
                        $verif = Downloads::where('episode_id', $request->episode_id)->where('user_id', $user->id)->first();
                        $user_id = $user->id;
                    }else{
                        $verif = Downloads::where('episode_id', $request->episode_id)->where('user_id', 0)->where('ip_address', $ip)->first();
                        $user_id = "0";
                    }
                    if ($verif){
                        $verif->time = $request->time;
                        $verif->save();
                    }else{
                        Downloads::create([
                            'episode_id'=> $episode->id,
                            'user_id'=> $user_id,
                            'serie_id' => $serie->id,
                            'qualite' => 'vue',
                            'time' => $request->time,
                            'ip_address'=>  $ip]);
                    }
                    return [true];
                }
            }
            return [false];
        }elseif($request->action == "theme"){
            $user->theme = $request->theme;
            $user->save();
             return [true];

        }

    }

}