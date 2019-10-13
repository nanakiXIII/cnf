<?php
namespace App\Http\Controllers\Api\Administration;


use App\Avancements;
use App\Http\Controllers\Controller;
use App\Http\Resources\avancementAdminRessource;
use App\postes;
use App\Serie;
use Illuminate\Http\Request;



class avancementsController extends Controller {
    public function index(){
        $avacenement = Avancements::all();
        return avancementAdminRessource::collection($avacenement);
    }


    public function create(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $request->validate(
            [
                'serie' => 'required',
                'saison' => 'required|integer',
                'numero' => 'required|integer',
            ]
        );
        $serie = Serie::findOrFail($request->serie['id']);
        $avancement = Avancements::create([ 'serie_id' => $request->serie['id'],
                                            'episode' => $request->numero,
                                            'saison_id' => $request->saison,
                                            'encodage' => $request->encodage,
                                            'adapt' => $request->adapt,
                                            'edition' => $request->edition,
                                            'qcheck' => $request->qcheck,
                                            'traduction' => $request->traduction,
                                            'time' => $request->time,
                                            'check' => $request->check,
                                            'type' => $request->serie['type'],
                                            'user_id' => $request->user()->id,
                                            'publication' => $request->publication,
                                            ]);
        if ($avancement){
            $response['success'] = true;
            $response['data'] = $avancement;
        }
        return response()->json($response);
    }
    public function update(request $request){
            $response = [
                'statut' => '200',
                'error' => false,
                'success' => false,
                'data' => null
            ];
            $request->validate(
                [
                    'id' => 'required|integer',
                    'numero' => 'required|integer',
                ]
            );
           $avancement = Avancements::findOrFail($request->id);
           $avancement->episode = $request->numero;
           $avancement->encodage = $request->encodage;
           $avancement->adapt = $request->adapt;
           $avancement->edition = $request->edition;
           $avancement->qcheck = $request->qcheck;
           $avancement->traduction = $request->traduction;
           $avancement->time = $request->time;
           $avancement->check = $request->check;
           $avancement->publication = $request->publication;
           $avancement->save();
            if ($avancement){
                $response['success'] = true;
                $response['data'] = $avancement;
            }
            return response()->json($response);
    }
    public function delete(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $avancement = Avancements::findOrFail($request->id);
        if($avancement){
            $avancement->delete();
            $response['success'] = true;
            $response['data'] = $avancement;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);

    }


}