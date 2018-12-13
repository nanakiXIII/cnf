<?php
namespace App\Http\Controllers\Api\Administration;


use App\Genres;
use App\Http\Controllers\Controller;
use App\post;
use App\postes;
use App\User;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class serieController extends Controller {


    public function index(Request $request){
        if ($request->data == "Ajouter"){
            return Genres::all();
        }


    }
    public function show(Request $request){


    }

    public function update(request $request){

        if ($request->action ==  "Information"){
            if (filter_var($request->url, FILTER_VALIDATE_URL)) {
                if ($request->choix == "Animeka"){
                    $string = 'https://www.animeka.com/animes/detail/';
                    if (!strstr($request->url,  $string)) {
                        abort(403, 'URL Invalide');
                    }
                }
            } else {
                abort(403, 'Cette URL a un format non adapté.');
            }

                $ch = curl_init($request->url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $raw = curl_exec($ch);
                curl_close($ch);
                $html = new \DOMDocument();
                @$html->loadHTML($raw);
                $xpath = new \DOMXPath($html);
                if ($request->choix == "Animeka"){
                    $domExemple = $xpath->query("//td[@class='animestxt']");
                    $vowels = array("TITRE ORIGINAL :","AUTEURS : ", "GENRES : ","u00a0" ,"& ", "ANNÉE DE PRODUCTION : ","ANNÉES DE PRODUCTION :", "STUDIO :", "GENRE : ", "AUTEUR :", "VOLUMES, TYPE & DURÉE :", "[", "]");
                }
                $i = 0;
                foreach ($domExemple as $exemple) {
                    if ($i == 1){
                        $value = 'titre';
                    }
                    elseif ($i == 2){
                        $value = 'annee';
                    }
                    elseif ($i == 3){
                        $value = 'studio';
                    }
                    elseif ($i == 5){
                        $value = 'auteur';
                    }
                    elseif ($i == 7){
                        $value = 'titre_alternatif';
                    }
                    elseif ($i == 4){
                        $value = 'genres';
                    }
                    elseif ($i == 9){
                        $value = 'synopsis';
                    }
                    else{
                        $value = $i;
                    }

                    trim($result[$value] = trim(preg_replace( "~\x{00a0}~siu", "", str_replace($vowels, "", $exemple->nodeValue))));
                    $i++;
                }
                $array = [];
                foreach ($tab = explode(" ", preg_replace( "~\x{00a0}~siu", "", $result['genres'])) as $g){
                    $genre = Genres::where('name','like', '%'.$g.'%')->first();

                    if ($genre){
                        array_push($array, $genre->id);
                    }
                    else{
                        $newGenre = Genres::create(['name' => ucfirst(strtolower($g))]);
                        array_push($array, $newGenre->id);
                    }



                }
            if ($result['synopsis'] == "Non disponible pour le moment, soyez le premier à écrire votre synopsis."){
                $result['synopsis'] = "";
            }


            $result['image'] = GoogleImageGrabber::grab($result['titre'],"","6" );
            $result['etat'] = 0;
            $result['imageChoix'] = "manuel";
            $result['type'] = 0;
            $result['genre'] = $array;
            $result['newGenre'] = Genres::all();
            return $result;
        }


    }



}