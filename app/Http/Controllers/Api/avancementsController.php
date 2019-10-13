<?php
namespace App\Http\Controllers\Api;


use App\Avancements;
use App\Http\Controllers\Controller;
use App\Http\Resources\avancementAdminRessource;
use App\Serie;
use Illuminate\Http\Request;



class avancementsController extends Controller {
    public function index(){
        $avacenement = Avancements::where('publication', 1)->get();
        return avancementAdminRessource::collection($avacenement);
    }

}