<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Http\Resources\roleAdminCollection;
use App\Http\Resources\roleAdminResource;
use App\Http\Resources\roleResource;
use App\postes;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class GradesController extends Controller {
    public function index(Request $request){
        $grades = Role::all();
        return new roleAdminCollection($grades);
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
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        if ($role){
            $role->permissions()->sync($request->permissionID);
            $response['success'] = true;
            $response['data'] = $role;
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
        $role = Role::findOrFail($request->id);
        if($role){
            $role->name = $request->name;
            $role->save();
            $role->permissions()->sync($request->permissionID);
            $response['success'] = true;
            $response['data'] = $role;
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
        $role = Role::findOrFail($request->id);
        if($role){
            $role->delete();
            $response['success'] = true;
            $response['data'] = $role;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);

    }


}