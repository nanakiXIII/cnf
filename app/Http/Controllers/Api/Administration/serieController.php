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
        $genre = json_decode($request->genre,true);
        if ($request->hasFile('banner')){
            $bannerName = time().'.'.$request->banner->getClientOriginalExtension();
            $request->banner->storeAs('public/images/banniere',$bannerName);
        }else{
            if (!empty($serie['bannerImage'])){
                $extension = pathinfo($serie['bannerImage'], PATHINFO_EXTENSION);
                $basename = pathinfo($serie['bannerImage'], PATHINFO_BASENAME);
                $bannerName = time().'.'.$extension;
                $file = file_get_contents($serie['bannerImage']);
                $save = file_put_contents(storage_path('app/public/images/banniere/'.$bannerName), $file);
            }else{
                $bannerName = null;
            }
        }
        if ($request->hasFile('image')){
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->storeAs('public/images/images',$imageName);
        } else{
            $extension = pathinfo($serie['coverImage']['large'], PATHINFO_EXTENSION);
            $basename = pathinfo($serie['coverImage']['large'], PATHINFO_BASENAME);
            $imageName = time().'.'.$extension;
            $file = file_get_contents($serie['coverImage']['large']);
            $save = file_put_contents(storage_path('app/public/images/images/'.$imageName), $file);
        }

        if ($request->type != "Animes"){
            if ($request->type == "Visual-Novel"){
                $studio = 'nop';
                $date = 000000;
                $serie['episodes'] = 0;
                $volumes = 0;
            }else{
                $date = $serie['startDate']['day'].'-'.$serie['startDate']['month'].'-'.$serie['startDate']['year'];
                $serie['episodes'] = $serie['chapters'];
                $volumes = $serie['volumes'];
                $studio = 'nop';
            }

        }else{
            $date = $serie['startDate']['day'].'-'.$serie['startDate']['month'].'-'.$serie['startDate']['year'];
            $volumes = 0;
            $studio = $serie['studios']['nodes'][0]['name'];
        }
        if (!empty($serie['staff'])){
            $staff = $serie['staff'];
        }else{
            $staff = "Pas de staff pour le moment";
        }
        $slug = str_slug($serie['title']['romaji']);
        $newSerie = Serie::create([
            'titre' => $serie['title']['romaji'],
            'titre_original' => $serie['title']['english'],
            'titre_alternatif' => $serie['title']['native'],
            'annee' => $date,
            'studio' => $studio,
            'auteur' => $bannerName,
            'episode' => $serie['episodes'],
            'oav' => 0,
            'films' => 0,
            'bonus' => 0,
            'scan' => $volumes,
            'ln' => 0,
            'vn' => 0,
            'synopsis' => $serie['synopsis'],
            'staff' =>$staff,
            'type' => $request->type,
            'publication' => $serie['etat'],
            'slug' => $slug,
            'image' => $imageName,
            'etat' => $serie['statut'],
            'userId' => $request->user()->id
        ]);
        if ($request->type != 'Visual-Novel'){
            $newSerie->genres()->sync($genre);
        }
        return $serie;
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
        if ($request->info['type'] == 'ANIME'){
            $type = 'animes';
        }else{
            $type = 'mangas';
        }
            $name = str_replace(':',' -',$request->info['title']['romaji']);
            $name = urlencode(strtolower($name));
            $result = [];
            $result['name'] = $name;
            $url = 'https://www.nautiljon.com/'.$type.'/'.$name.'.html';
            $ch = curl_init($url);
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
                elseif ($i == 97){
                    $value = 'annee';
                }
                elseif ($i == 102){
                    $value = 'studio';
                }
                elseif ($i == 92){
                    $value = 'titre_alternatif';
                }
                elseif (preg_match("/Genres/i", $exemple->nodeValue)){
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
            if (!empty($result['genres'])){
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

            $result['genre'] = $array;
            $result['statut'] = 0;
            $result['etat'] = 0;
            $result['newGenre'] = Genres::all();

            return $result;

    }






}