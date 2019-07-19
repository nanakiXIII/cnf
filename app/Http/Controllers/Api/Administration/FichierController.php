<?php
namespace App\Http\Controllers\Api\Administration;


use App\Episodes;
use App\Ftp;
use App\Http\Controllers\Controller;
use App\Jobs\downloadFile;
use App\Jobs\ExtractArchiveJob;
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
        $reponse->date = Carbon::now();

        return json_encode($reponse);

    }

    public function archive(request $request){
        if ($request->hasFile('file')){
            $request->file->storeAs('public/', $request->file->getClientOriginalName());
        }
        return "ok";


    }

    public function create(request $request){
        $reponse = new class{};

        $saison = Saisons::findOrFail($request->idSaison);
        $serie = Serie::findOrFail($saison->serie_id);
        if ($request->type == 'Chapitre'){
            $dvd = 'non';
            $hd = "serie/".$saison->serie->id."/$saison->id/$request->file";
            $fhd = 'non';
            Storage::disk('public')->move($request->file,"serie/".$saison->serie->id."/$saison->id/$request->file");
        }
        else{
            $dvd = $request->dvd;
            $hd = $request->hd;
            $fhd = $request->fhd;
        }
        if ($saison){
            $episode = Episodes::create([
                "name" => $request->name,
                "numero" => $request->numero,
                "type" => $request->type,
                "dvd" => $dvd,
                "hd" => $hd,
                "fhd" => $fhd,
                "publication" => $request->publication,
                "serie_id" => $request->idSerie,
                "saisons_id" => $request->idSaison,
                "etat" => 0,
                "streaming" => 0
            ]);
            if ($episode){
                $reponse->data = true;
                $reponse->episode = $episode;
                if ($episode->type == 'Chapitre'){
                    ExtractArchiveJob::dispatch($episode);
                    //downloadFile::dispatch($episode, $request->streaming, $request->user());
                }
                else{
                    downloadFile::dispatch($episode, $request->streaming, $request->user());
                }
            }
            //downloadFile::dispatch($episode, $request->streaming, $request->user());
        }
        else{
            $reponse->data = false;
            $reponse->action = "nouveauEpisode";
            $reponse->episode = null;
        }

        return json_encode($reponse);


    }
    public function update(request $request){
        $reponse = new class{};

        $saison = Saisons::findOrFail($request->saisons_id);
        $episode = Episodes::findOrFail($request->id);
        if ($episode){
            if ($request->type == 'Chapitre'){
                if ($request->file){
                    Storage::disk('public')->delete($episode->hd);
                    Storage::disk('public')->deleteDirectory("serie/".$saison->serie->id."/".$saison->id.'/'.$episode->id);
                    $hd = "serie/".$saison->serie->id."/$saison->id/$request->file";
                    Storage::disk('public')->move($request->file,"serie/".$saison->serie->id."/$saison->id/$request->file");
                    $episode->streaming = 0;
                }else{
                    $hd = $episode->hd;
                }
                $dvd = 'non';
                $fhd = 'non';

            }
            else{
                $dvd = $request->dvd;
                $hd = $request->hd;
                $fhd = $request->fhd;
                if ($request->dvd != $episode->dvd){
                    $episode->streaming = 0;
                }
                if ($request->hd != $episode->hd){
                    $episode->streaming = 0;
                }
                if ($request->fhd != $episode->fhd){
                    $episode->streaming = 0;
                }
            }
        }

        if ($episode){

                $episode->name = $request->name;
                $episode->numero = $request->numero;
                $episode->type = $request->type;
                $episode->dvd = $dvd;
                $episode->hd = $hd;
                $episode->fhd = $fhd;
                $episode->publication = $request->publication;
                $episode->serie_id = $request->serie_id;
                $episode->saisons_id = $request->saisons_id;
                $episode->etat = 0;
                $episode->save();

                $reponse->data = true;
                $reponse->episode = $episode;

            //downloadFile::dispatch($episode, $request->streaming, $request->user());
        }
        else{
            $reponse->data = false;
            $reponse->episode = null;
        }

        return json_encode($reponse);

    }
    public function delete(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $episode = Episodes::findOrFail($request->id);
        if ($episode){
            Storage::disk('public')->delete($episode->hd);
            Storage::disk('public')->deleteDirectory("serie/".$episode->serie_id."/".$episode->saions_id.'/'.$episode->id);
            $episode->delete();
            $response['success'] = true;
        }
        return response()->json($response);

    }


}