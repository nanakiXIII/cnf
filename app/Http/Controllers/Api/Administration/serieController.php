<?php
namespace App\Http\Controllers\Api\Administration;


use App\Genres;
use App\Http\Controllers\Controller;
use App\post;
use App\postes;
use App\Serie;
use App\User;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use function functions\slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                elseif ($request->choix == "Nautiljon"){
                    $string = 'https://www.nautiljon.com/';
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
                $i = 0;
                if ($request->choix == "Animeka"){
                    $domExemple = $xpath->query("//td[@class='animestxt']");
                    $vowels = array("TITRE ORIGINAL :","AUTEURS : ", "GENRES : ","u00a0" ,"& ", "ANNÉE DE PRODUCTION : ","ANNÉES DE PRODUCTION :", "STUDIO :", "GENRE : ", "AUTEUR :", "VOLUMES, TYPE & DURÉE :", "[", "]");

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
                }
                elseif ($request->choix == "Nautiljon"){
                    $domExemple = $xpath->query("//li");
                    $vowels = array("Saison : ","automne ", "hiver ","printemps " ,"été ", "Studio d'animation : ","Studio d'animation : ", "Genres : ", " -", "Titre original : ", "VOLUMES, TYPE & DURÉE :", "[", "]");
                    foreach ($domExemple as $exemple) {
                        if ($i == 80){
                            $value = 'titre';
                        }
                        elseif ($i == 97){
                            $value = 'annee';
                        }
                        elseif ($i == 102){
                            $value = 'studio';
                        }
                        elseif ($i == 92){
                            $value = 'titre_alternatif';
                        }
                        elseif ($i == 99){
                            $value = 'genres';
                        }

                        else{
                            $value = $i;
                        }

                        trim($result[$value] = trim(preg_replace( "~\x{00a0}~siu", "", str_replace($vowels, "", $exemple->nodeValue))));
                        $i++;
                    }
                    $domExemple = $xpath->query("//div[@class='description']");
                    $vowels = array("Saison : ","automne ", "hiver ","printemps " ,"été ", "Studio d'animation : ","Studio d'animation : ", "Genres : ", " -", "Titre original : ", "VOLUMES, TYPE & DURÉE :", "[", "]");
                    foreach ($domExemple as $exemple) {
                            $value = $i;
                        trim($result['synopsis'] = trim(preg_replace( "~\x{00a0}~siu", "", str_replace($vowels, "", $exemple->nodeValue))));
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
                }



            $result['image'] = GoogleImageGrabber::grab($result['titre'],"","6" );
            $result['etat'] = 0;
            $result['imageChoix'] = "manuel";
            $result['type'] = 0;
            $result['episode'] = 0;
            $result['oav'] = 0;
            $result['films'] = 0;
            $result['bonus'] = 0;
            $result['scan'] = 0;
            $result['ln'] = 0;
            $result['vn'] = 0;
            $result['genre'] = $array;
            $result['newGenre'] = Genres::all();
            return $result;
        }
        elseif ($request->action == 'newSerie'){
            $slug = str_slug($request->titre);
            $dossier = "serie/$request->type/$slug/";
            $genre = explode(',', $request->genre);
            if ($request->imageChoix == 'auto'){
                $extension = pathinfo($request->imagecheck, PATHINFO_EXTENSION);
            }
            else{
                $extension = $request->file->extension();
            }
            $titre = $slug.'.'.$extension;
            $newSerie = Serie::create([
                'titre' => $request->titre,
                'titre_original' => $request->titre_original,
                'titre_alternatif' => $request->titre_alternatif,
                'annee' => $request->annee,
                'studio' => $request->studio,
                'auteur' => $request->auteur,
                'episode' => $request->episode,
                'oav' => $request->oav,
                'films' => $request->film,
                'bonus' => $request->bonus,
                'scan' => $request->scan,
                'ln' => $request->ln,
                'vn' => $request->vn,
                'synopsis' => $request->synopsis,
                'staff' => $request->staff,
                'type' => $request->type,
                'publication' => true,
                'slug' => $slug,
                'image' => '/storage/'.$dossier.$slug.'.'.$extension,
                'etat' => $request->etat
            ]);
            $newSerie->genres()->sync($genre);

            if ($request->imageChoix == 'auto'){
                $file = file_get_contents($request->imagecheck);
                $save = file_put_contents(storage_path('app/public/'.$titre), $file);
            }
            else{
                $request->file->storeAs('public', $titre);
            }
            Storage::disk('public')->move('/'.$titre, $dossier.$titre);

            return [true];
        }


    }



}