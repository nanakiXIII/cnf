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
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;


class gestionController extends Controller {
    public function index(){
        $episode = Episodes::orderBy('type', 'DESC')->orderBy('serie_id', 'ASC')->orderBy('numero', 'ASC')->get();
        return fichierAdminResource::collection($episode);
    }

    public function show(Request $request){

    }

    public function update(request $request){
        $episode = Episodes::findOrFail($request->id);
        if ($episode->type == 'Episode'){
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
            if ($request->etat == 'image'){
                imageVideo::dispatch($episode, $request->user());
            }
            if ($request->etat == 'delete'){
                $episode->etat = 5;
                $episode->save();
                //Storage::disk('public')->delete("serie/$episode->serie_id/$episode->saisons_id/$episode->id/$episode->id.mkv");
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