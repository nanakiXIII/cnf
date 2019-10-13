<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Http\Resources\MembreAdminCollection;
use App\Http\Resources\MembreAdminResource;
use App\post;
use App\postes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class utilisateurController extends Controller {

    public function index(Request $request){
        $user =  User::all();
        return new MembreAdminCollection($user);
    }
    public function show(Request $request){
    }
    public function update(request $request){
        $reponse = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $equipe = false;
        if ($request->equipe){
            $equipe = true;
        }
        $user = User::findOrFail($request->id);
        $user->roles()->sync($request->roleID);
        $user->competence()->sync($request->posteID);
        $user->equipe = $equipe;
        $user->save();
        $reponse['success'] = true;
        $reponse['data'] = $user;
        return $reponse;
    }

    public function lupdate(request $request){
        if ($request->action == "roles"){
            $equipe = false;
            if ($request->equipe){
                $equipe = true;
            }
            $user = User::findOrFail($request->user_id);
            $user->roles()->sync($request->checkbox);
            $user->competence()->sync($request->postes);
            $user->equipe = $equipe;
            $user->save();
            return $user;
        }
        elseif ($request->action == "postesMod"){

            $request->validate(
                [
                    'name' => 'required|string',
                    'site' => 'required|string',
                ]
            );

            $poste = postes::findOrFail($request->id);
            $poste->name = $request->name;
            $poste->site = $request->site;
            $poste->save();
             return $poste;
        }
        elseif ($request->action == "permissionMod"){

            $request->validate(
                [
                    'name' => 'required|string',
                ]
            );

            $permisison = Permission::findOrFail($request->id);
            $permisison->name = $request->name;
            $permisison->save();
            return $permisison;
        }
        elseif ($request->action == "postes"){

            $request->validate(
                [
                    'name' => 'required|string',
                    'site' => 'required|string',
                ]
            );

            $poste = postes::create(['name' => $request->name, 'site' => $request->site]);
            return $poste;
        }
        elseif ($request->action == "permission"){
            $request->validate(
                [
                    'name' => 'required|string',
                ]
            );

            $permission = Permission::create(['name' => $request->name, 'guard_name' => 'web']);
            return $permission;
        }
        elseif ($request->action == "Aroles"){

            $request->validate(
                [
                    'name' => 'required|string',
                ]
            );

            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
            $role->save();
            return ['id'=> $role->id, 'name'=> $role->name, 'permissionsID'=> []];
        }
        elseif ($request->action == "rolesMod"){

            $request->validate(
                [
                    'name' => 'required|string',
                ]
            );

            $role = Role::find($request->id);
            $role->permissions()->sync($request->permissions);
            $role->save();
            return ['id'=> $role->id, 'name'=> $role->name, 'permissionsID'=> $request->permissions, 'permissions' => $role->permissions()->pluck('name')];
        }
        elseif ($request->action == "delete"){
            $request->validate(
                [
                    'id' => 'required|integer',
                ]
            );
            $poste = postes::findOrFail($request->id);
            $poste->delete();
            return [true];
        }
        elseif ($request->action == "permissionDelete"){
            $request->validate(
                [
                    'id' => 'required|integer',
                ]
            );
            $permisison = Permission::findOrFail($request->id);
            $permisison->delete();
            return [true];
        }
        elseif ($request->action == "rolesDelete"){
            $role = Role::find($request->id);
            $role->permissions()->detach();
            $role->delete();
            return [true];
        }


    }



}