<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\postes;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller {
    public function index(Request $request){
        return Permission::all();
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
            ]
        );
        $poste = Permission::create([  'name' => $request->name,'guard_name' => 'web']);
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
            ]
        );
        $poste = Permission::findOrFail($request->id);
        if($poste){
            $poste->name = $request->name;
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
        $poste = Permission::findOrFail($request->id);
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