<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Events\userEvent;
use App\Http\Controllers\Controller;
use App\Repository\AccountRepository;
use App\Serie;
use App\User;
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
        return $this->accountRepository->getListeAbonnement($request->user());

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
        $user = User::findOrFail($request->user()->id);
        if ($request->password){
            if ($request->email != $user->email){
                $request->validate(
                    [
                        'email' => 'required|email|unique:users',
                        'password' => 'required|confirmed|min:6',
                    ]
                );
            }else{
                $request->validate(
                    [
                        'password' => 'required|confirmed|min:6',
                    ]
                );
            }
            $user->password = bcrypt( $request->password);

        }else{
            if ($request->email != $user->email){
                $request->validate(
                    [
                        'email' => 'required|email|unique:users',
                    ]
                );
            }
        }
        $user->email = $request->email;
        $user->notification = $request->notification;
        $user->theme = $request->theme;
        $user->save();
        broadcast(new userEvent($request->user('api'), 'reload'));
        return $user;
    }

    public function updatetest(Request $request){
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

    public function abonnement(Request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $user = $request->user('api');
        if ($user){
            $serie = Serie::find($request->serie_id);
            if ($serie){
                $response['success'] = true;
                if (!$user->series()->find($request->serie_id)){
                    $user->series()->attach($serie);
                    $response['data'] = true;
                }else{
                    $user->series()->detach($serie);
                    $response['data'] = false;
                }
            }else{
                $response['error'] = true;
                $response['data'] = 'not Serie';
            }
        }else{
            $response['error'] = true;
            $response['data'] = 'not Auth';
        }
        broadcast(new userEvent($request->user('api'), 'reload'));
        return $response;
    }

    public function avatar(Request $request){
        $tab =[];
        $tab['ok'] = 'ok';
        $tab['avatar'] = '';
        if ($request->hasFile('file')){
            $name = time().$request->file->getClientOriginalName();
            $request->file->storeAs('public/images/avatar/', $name);
            $tab['avatar'] = $name;
            $user = User::find($request->user()->id);
            $user->avatar = '/storage/images/avatar/'.$name;
            $user->save();
        }
        broadcast(new userEvent($request->user('api'), 'reload'));
        return $tab;
    }

}