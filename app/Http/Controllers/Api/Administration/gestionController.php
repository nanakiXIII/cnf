<?php
namespace App\Http\Controllers\Api\Administration;


use App\Episodes;
use App\Ftp;
use App\Http\Controllers\Controller;
use App\Http\Resources\fichierAdminResource;
use App\Jobs\downloadFile;
use App\Jobs\encodageVideo;
use App\Jobs\ExtractArchiveJob;
use App\Jobs\imageVideo;
use App\Saisons;
use App\Serie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;

class gestionController extends Controller {
    public function index(){
        $episode = Episodes::orderBy('type', 'DESC')->orderBy('serie_id', 'ASC')->orderBy('numero', 'ASC')->get();
        return fichierAdminResource::collection($episode);
    }

    public function show(Request $request){

    }

    public function update(request $request){
        $episode = Episodes::findOrFail($request->id);
        if ($episode->type != 'Chapitre'){
            if ($request->etat == 0){
                $episode->etat = $request->etat;
                $episode->save();
                downloadFile::dispatch($episode, "inconnu", $request->user());
            }
            if($request->etat == 2){
                $episode->etat = $request->etat;
                $episode->save();
                encodageVideo::dispatch($episode, $request->user(), "$episode->id.mkv");
            }
            if ($request->etat == '4'){
                imageVideo::dispatch($episode, $request->user());
            }
            if ($request->etat == '5'){
                $delete = Storage::disk('public')->delete("serie/$episode->serie_id/$episode->saisons_id/$episode->id/$episode->id.mkv");

                if($delete){
                 $array = ["embed" =>['title'=>"[Suppression de la vidéo MKV] ".$episode->serie->titre." $episode->type $episode->numero",
                             'author' =>['name' => $request->user()->name,'icon_url' => env('APP_URL').$request->user()->avatar],
                             'thumbnail' => ['url' => env('APP_URL').$episode->image]]];
                $channel = app(Discord::class)->send(253979896303321089, $array );
                 $episode->etat = 5;
                $episode->save();
                }


            }
        }
        if ($episode->type == 'Chapitre'){
            $episode->etat = 0;
            $episode->save();
            ExtractArchiveJob::dispatch($episode);
        }
        else{

        }
        return 'ok';

    }
    public function delete(request $request){

    }


}