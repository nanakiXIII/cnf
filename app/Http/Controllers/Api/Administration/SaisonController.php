<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Saisons;
use App\Serie;
use Illuminate\Http\Request;



class SaisonController extends Controller {
    public function index(Request $request){


    }
    public function images(Request $request){



    }
    public function show(Request $request){


    }

    public function create(request $request){
        $reponse = new class{};
        $serie = Serie::findOrFail($request->id);
        if ($serie){
            $saison = Saisons::create([
                'name' => $request->name,
                'numero' => $request->numero,
                'type' => $request->type,
                'serie_id' => $request->id,
                'publication' => $request->publication,
                'nosaison' => $request->nosaison,
            ]);
            if ($saison){
                $reponse->data = true;
                $reponse->saison = $saison;
            }
            else{
                $reponse->data = false;
                $reponse->saison = null;
            }
        }
        return json_encode($reponse);


    }
    public function update(request $request){
        $reponse = new class{};
        $reponse->data = false;
        $reponse->saison = null;
        $serie = Serie::findOrFail($request->serie_id);
        if ($serie){
            $saison = Saisons::findOrFail($request->id);
            if ($saison){
                $saison->update([
                    'name' => $request->name,
                    'numero' => $request->numero,
                    'type' => $request->type,
                    'serie_id' => $request->serie_id,
                    'publication' => $request->publication,
                    'nosaison' => $request->nosaison,
                ]);
                $reponse->data = true;
                $reponse->saison = $saison;
            }
        }
        return json_encode($reponse);


    }
    public function delete(request $request){
        $reponse = new class{};
        $reponse->data = false;
        $reponse->saison = null;
        $saison = Saisons::findOrFail($request->id);
            if ($saison){
                $saison->delete();
                $reponse->data = true;
        }
        return json_encode($reponse);

    }


}