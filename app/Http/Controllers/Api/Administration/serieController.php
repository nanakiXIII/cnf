<?php
namespace App\Http\Controllers\Api\Administration;


use App\Downloads;
use App\Genres;
use App\Http\Controllers\Controller;
use App\Http\Resources\SerieAdminCollection;
use App\Http\Resources\SerieAdminResource;
use App\Http\Resources\SeriesAdminCollection;
use App\post;
use App\postes;
use App\Saisons;
use App\Serie;
use App\User;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use Carbon\Carbon;
use function functions\slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class serieController extends Controller {
    public function index(){
        $serie = Serie::orderBy('publication')->orderBy('etat')->orderBy('titre')->get();
        return new SeriesAdminCollection($serie);
    }

    public function show(Request $request){
        $serie = Serie::where('slug', $request->slug)->where('id', $request->id)->firstOrFail();
        return new SerieAdminResource($serie);


        $saison = $serie->saisons()->orderBy('type')->orderBy('numero')->get();
        foreach ($saison as $s){
            $s->episodes = $s->episodes()->orderBy('type')->orderBy('numero')->get();
        }
        $tab = [];
        $serie->genres = $serie->genres;

        $tb['series'] =  $serie;
        $tb['saison'] =  $saison;
        return $tb;

    }
    public function statistique(Request $request){
        $reponse = new class{};
        $serie = Serie::findOrFail($request->id);
        $vue = Downloads::where('serie_id', $serie->id)->orderBy('created_at')->get()->groupBy(function($val) {
            return Carbon::parse($val->created_at)->format('F Y');
        });
        $vues = [];
        $dates = [];
        $download = [];
        $visionnage = [];
        $telechargement = [];
        foreach ($vue as $date => $value){
            $dates[] = $date;
            foreach ($value as $key => $v){
                if ($v->qualite == 'vue'){
                    $vues[$date][] = $v;
                }else{
                    $download[$date][] = $v;
                }
            }
        }
        foreach ($dates as $v){
            if (array_key_exists($v, $download)){
                $telechargement[] = count($download[$v]);
            }else{
                $telechargement[] = 0;
            }
            if (array_key_exists($v, $vues)){
                $visionnage[] = count($vues[$v]);
            }else{
                $visionnage[] = 0;
            }
        }
        $reponse->download = $telechargement;
        $reponse->date = $dates;
        $reponse->vues = $visionnage;

        return json_encode($reponse);
    }

    public function create(request $request){
        $serie = json_decode($request->serie,true);
        $bannerName = null;
        $imageName = null;
        $date = 000000;
        if ($request->hasFile('banniere')){
            $bannerName = time().'.'.$request->banniere->getClientOriginalExtension();
            $request->banniere->storeAs('public/images/banniere',$bannerName);
        }else{
           if($serie['bannerImage']){
               $extension = pathinfo($serie['bannerImage'], PATHINFO_EXTENSION);
               $basename = pathinfo($serie['bannerImage'], PATHINFO_BASENAME);
               $bannerName = time().'.'.$extension;
               $file = file_get_contents($serie['bannerImage']);
               $save = file_put_contents(storage_path('app/public/images/banniere/'.$bannerName), $file);
           }
        }
         if ($request->hasFile('image')){
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->storeAs('public/images/images',$imageName);
        } else{
            if($serie['coverImage']['large']){
               $extension = pathinfo($serie['coverImage']['large'], PATHINFO_EXTENSION);
               $basename = pathinfo($serie['coverImage']['large'], PATHINFO_BASENAME);
               $imageName = time().'.'.$extension;
               $file = file_get_contents($serie['coverImage']['large']);
               $save = file_put_contents(storage_path('app/public/images/images/'.$imageName), $file);
           }
        }
        if($serie['type'] == "MANGA"){
           $serie['type'] = 'Scantrad';
        }elseif($serie['type'] == "ANIME"){
            $serie['type'] = 'Animes';
        }
        if($serie['type'] != 'Animes'){
            $serie['studio'] = 'nop';
            $serie['episodes'] = $serie['chapters'];
        }else{
            $serie['studio'] = $serie['studios']['nodes'][0]['name'];
        }
        if ($serie['type'] != "Visual-Novel"){
            $date = $serie['startDate']['day'].'-'.$serie['startDate']['month'].'-'.$serie['startDate']['year'];
        }
        if(empty($serie['staff'])){
            $serie['staff'] = "L'équipe est pas encore disponible";
        }
        $slug = str_slug($serie['title']['romaji']);

        $newSerie = Serie::create([
                    'titre' => $serie['title']['romaji'],
                    'titre_original' => $serie['title']['english'],
                    'titre_alternatif' => $serie['title']['native'],
                    'annee' => $date,
                    'studio' => $serie['studio'],
                    'auteur' => $bannerName,
                    'episode' => $serie['episodes'],
                    'oav' => 0,
                    'films' => 0,
                    'bonus' => 0,
                    'scan' => $serie['volumes'],
                    'ln' => 0,
                    'vn' => 0,
                    'synopsis' => $serie['synopsis'],
                    'staff' =>$serie['staff'],
                    'type' => $serie['staff'],
                    'publication' => $serie['publication'],
                    'slug' => $slug,
                    'image' => $imageName,
                    'etat' => $serie['statut'],
                    'userId' => $request->user()->id,
                    'team' => $serie['team']
                ]);
                if ($newSerie){
                    $newSerie->genres()->sync($serie['genres']);
                }
        return json_encode($newSerie);
    }
    public function update(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $data = json_decode($request->serie,true);
        $serie = Serie::findOrFail($data['id']);
        if ($serie){
            if ($request->hasFile('banniere')){
                $bannerName = time().'.'.$request->banniere->getClientOriginalExtension();
                $request->banniere->storeAs('public/images/banniere',$bannerName);
            }else{
                $bannerName = $serie->auteur;
            }
            if ($request->hasFile('image')){
                $imageName = time().'.'.$request->image->getClientOriginalExtension();
                $request->image->storeAs('public/images/images',$imageName);
            }else{
                $imageName = $serie->image;
            }
            if (empty($data['synopsis'])){
                $response['error']=true;
                $response['data'] = "Le champs synopsis est vide";
                return response()->json($response);
            }
            if (empty($data['staff'])){
                $data['staff'] = "Pas de staff pour le moment";
            }
            $serie->titre = $data['titre'];
            $serie->titre_original = $data['titre_original'];
            $serie->titre_alternatif = $data['titre_alternatif'];
            $serie->annee = $data['annee'];
            $serie->studio = $data['studio'];
            $serie->etat =$data['etat'];
            $serie->type = $data['type'];
            $serie->scan = $data['scan'];
            $serie->episode = $data['episode'];
            $serie->synopsis = $data['synopsis'];
            $serie->staff = $data['staff'];
            $serie->image = $imageName;
            $serie->auteur = $bannerName;
            $serie->publication = $data['publication'];
            $serie->save();
            $serie->genres()->sync($data['genresId']);
            $response['success'] = true;
        }
        else{
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
        $serie = Serie::findOrFail($request->id);
        Storage::disk('public')->deleteDirectory('serie/'.$serie->id);
        if($serie){
            $serie->delete();
            $response['success'] = true;
            $response['data'] = $serie;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);
    }

    public function informations(request $request){
            $ch = curl_init($request->url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $raw = curl_exec($ch);
            curl_close($ch);
            $html = new \DOMDocument();
            @$html->loadHTML($raw);
            $xpath = new \DOMXPath($html);
            $i = 0;
            $domExemple = $xpath->query("//li");
            $vowels = array("Saison : ","automne ", "hiver ","printemps ", "Genres : " ,"été ", "Studio d'animation : ","Studio d'animation : ",  " -", "Titre original : ", "VOLUMES, TYPE & DURÉE :", "[", "]");
            foreach ($domExemple as $exemple) {
                if ($i == 80){
                    $value = 'titre';
                }
                elseif (preg_match("/Genres/i", $exemple->nodeValue)){
                    $value = 'genres';
                }

                else{
                    $value = $i;
                }
                if(!is_numeric($value) ){
                    trim($result[$value] = trim(preg_replace( "~\x{00a0}~siu", "", str_replace($vowels, "", $exemple->nodeValue))));
                }

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
            function no_accent($str){
                $accents = array('À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'Ā' => 'A', 'ā' => 'a', 'Ă' => 'A', 'ă' => 'a', 'Ą' => 'A', 'ą' => 'a', 'Ç' => 'C', 'ç' => 'c', 'Ć' => 'C', 'ć' => 'c', 'Ĉ' => 'C', 'ĉ' => 'c', 'Ċ' => 'C', 'ċ' => 'c', 'Č' => 'C', 'č' => 'c', 'Ð' => 'D', 'ð' => 'd', 'Ď' => 'D', 'ď' => 'd', 'Đ' => 'D', 'đ' => 'd', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'Ē' => 'E', 'ē' => 'e', 'Ĕ' => 'E', 'ĕ' => 'e', 'Ė' => 'E', 'ė' => 'e', 'Ę' => 'E', 'ę' => 'e', 'Ě' => 'E', 'ě' => 'e', 'Ĝ' => 'G', 'ĝ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ġ' => 'G', 'ġ' => 'g', 'Ģ' => 'G', 'ģ' => 'g', 'Ĥ' => 'H', 'ĥ' => 'h', 'Ħ' => 'H', 'ħ' => 'h', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'Ĩ' => 'I', 'ĩ' => 'i', 'Ī' => 'I', 'ī' => 'i', 'Ĭ' => 'I', 'ĭ' => 'i', 'Į' => 'I', 'į' => 'i', 'İ' => 'I', 'ı' => 'i', 'Ĵ' => 'J', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'L', 'ĺ' => 'l', 'Ļ' => 'L', 'ļ' => 'l', 'Ľ' => 'L', 'ľ' => 'l', 'Ŀ' => 'L', 'ŀ' => 'l', 'Ł' => 'L', 'ł' => 'l', 'Ñ' => 'N', 'ñ' => 'n', 'Ń' => 'N', 'ń' => 'n', 'Ņ' => 'N', 'ņ' => 'n', 'Ň' => 'N', 'ň' => 'n', 'ŉ' => 'n', 'Ŋ' => 'N', 'ŋ' => 'n', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'Ō' => 'O', 'ō' => 'o', 'Ŏ' => 'O', 'ŏ' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Ŕ' => 'R', 'ŕ' => 'r', 'Ŗ' => 'R', 'ŗ' => 'r', 'Ř' => 'R', 'ř' => 'r', 'Ś' => 'S', 'ś' => 's', 'Ŝ' => 'S', 'ŝ' => 's', 'Ş' => 'S', 'ş' => 's', 'Š' => 'S', 'š' => 's', 'ſ' => 's', 'Ţ' => 'T', 'ţ' => 't', 'Ť' => 'T', 'ť' => 't', 'Ŧ' => 'T', 'ŧ' => 't', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'Ũ' => 'U', 'ũ' => 'u', 'Ū' => 'U', 'ū' => 'u', 'Ŭ' => 'U', 'ŭ' => 'u', 'Ů' => 'U', 'ů' => 'u', 'Ű' => 'U', 'ű' => 'u', 'Ų' => 'U', 'ų' => 'u', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ý' => 'Y', 'ý' => 'y', 'ÿ' => 'y', 'Ŷ' => 'Y', 'ŷ' => 'y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'ź' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ž' => 'Z', 'ž' => 'z');
                return strtr($str, $accents);
            }
            if (!empty($result['genres'])){
                foreach ($tab = explode(" ", preg_replace( "~\x{00a0}~siu", "", $result['genres'])) as $g){
                 $g = no_accent($g);
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
            $result['genres'] = $array;
            return json_encode($result);

    }






}