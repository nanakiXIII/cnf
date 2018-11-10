<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class utilisateurController extends Controller {


    public function index(Request $request){
        $user =  User::all();
        $us = [];
        $rolesAll = Role::all();
        foreach($user as $u){
            $tab = [];
            foreach ($u->roles()->get() as $roles){
                foreach ($roles->permissions()->pluck('name') as $perm){
                    if (!in_array($perm, $tab)){
                        $tab[] =  $perm;
                    }
                }
            }
            $u->permission = $tab;
            $u->role = $u->roles()->pluck('name');
            $u->roleID = $u->roles()->pluck('id');
            $us['data'][] = $u;
        }
        $us['roles'] = $rolesAll;
        return $us;

    }
    public function show(Request $request){
        $response = new class{};
        $post = post::where('publication', true)->where('slug', $request->slug)->first();
        $simulare = post::where('type', $post->type)->orderBy('publish_at', 'DESC')->where('id', '!=', $post->id)->limit(4)->get();
        $response->simulare = $simulare;
        $response->news = $post;
        return json_encode($response);

    }



}