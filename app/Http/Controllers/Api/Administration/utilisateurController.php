<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\post;
use App\postes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class utilisateurController extends Controller {


    public function index(Request $request){
        $user =  User::all();
        $us = [];
        $rolesAll = Role::all();
        $Postes = postes::all();
        foreach($user as $u){
            $tab = [];
            foreach ($u->roles()->get() as $roles){
                foreach ($roles->permissions()->pluck('name') as $perm){
                    if (!in_array($perm, $tab)){
                        $tab[] =  $perm;
                    }
                }
            }
            $u->posteID = $u->competence()->pluck('postes_id');
            $u->poste = $u->competence;
            $u->permission = $tab;
            $u->role = $u->roles()->pluck('name');
            $u->roleID = $u->roles()->pluck('id');
            $us['data'][] = $u;
        }
        $us['postes'] = $Postes;
        $us['roles'] = $rolesAll;
        return $us;

    }
    public function show(Request $request){


    }

    public function update(request $request){
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


    }



}