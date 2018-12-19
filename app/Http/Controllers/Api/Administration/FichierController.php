<?php
namespace App\Http\Controllers\Api\Administration;


use App\Episodes;
use App\Ftp;
use App\Http\Controllers\Controller;
use App\Jobs\downloadFile;
use App\Saisons;
use App\Serie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;


class FichierController extends Controller {
    public function index(Request $request){

        $episode = Episodes::all();
        $date =  Ftp::first();
        $episodeFichier = [];
        foreach ($episode as $e){
            $episodeFichier[] .= $e->dvd;
            $episodeFichier[] .= $e->hd;
            $episodeFichier[] .= $e->fhd;
        }
        $episodeFichier = array_prepend($episodeFichier, '.htaccess');
        $fichiers = Ftp::whereNotIn('fichier', $episodeFichier)->pluck('fichier')->toArray();

        $reponse = new class{};
        $reponse->data = true;
        $reponse->fichier = $fichiers;
        $reponse->date = $date->created_at;
        $reponse->action = "ListeFichier";
        return json_encode($reponse);


    }

    public function show(Request $request){

    }

    public function ftpUpdate(Request $request){


    $test = ['content'=>'Sa marche','body'=>'je ne sais pas', "tts" =>false];


        $files = Storage::disk('ftp')->allFiles();
        $files = array_filter($files, function($str){
            return strpos($str, "_h5ai") === false;
        });
        Ftp::truncate();
        foreach ($files as $file){
            ftp::create(['fichier'=>$file]);
        }
        $reponse = new class{};
        $reponse->data = true;
        $reponse->fichier = $files;
        $reponse->date = Carbon::now()->addHour(1);
        $reponse->action = "MiseAJour";


        return json_encode($reponse);

    }

    public function create(request $request){
        $reponse = new class{};
        $serie = Serie::findOrFail($request->idSerie);
        $saison = Saisons::findOrFail($request->idSaison);
        if ($saison){
            $episode = Episodes::create([
                "name" => $request->name,
                "numero" => $request->numero,
                "type" => $request->type,
                "dvd" => $request->dvd,
                "hd" => $request->hd,
                "fhd" => $request->fhd,
                "publication" => $request->publication,
                "serie_id" => $request->idSerie,
                "saisons_id" => $request->idSaison,
                "etat" => 0,
                "streaming" => 0
            ]);
            if ($episode){
                $reponse->data = true;
                $reponse->action = "nouveauEpisode";
                $reponse->episode = $episode;
            }
            downloadFile::dispatch($episode, $request->streaming, $request->user());
        }
        else{
            $reponse->data = false;
            $reponse->action = "nouveauEpisode";
            $reponse->episode = null;
        }

        return json_encode($reponse);


    }
    public function update(request $request){


    }
    public function delete(request $request){


    }


}