<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Http\Resources\NewsAdminCollection;
use App\Http\Resources\NewsAdminResource;
use App\post;
use App\postes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;
use phpDocumentor\Reflection\Types\Null_;


class PostesController extends Controller {
    public function index(Request $request){
        return postes::all();
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
                'name' => 'required|string',
                'site' => 'required|string',
            ]
        );
        $poste = postes::create([  'name' => $request->name,'site' => $request->site]);
            if ($poste){
                $response['success'] = true;
                $response['data'] = $poste;
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
                'name' => 'required|string',
                'site' => 'required|string',
            ]
        );
        $poste = postes::findOrFail($request->id);
        if($poste){
            $poste->name = $request->name;
            $poste->site = $request->site;
            $poste->save();
            $response['success'] = true;
            $response['data'] = $poste;
        }else{
            $response['error'] = true;
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
        $poste = postes::findOrFail($request->id);
        if($poste){
            $poste->delete();
            $response['success'] = true;
            $response['data'] = $poste;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);

    }


}