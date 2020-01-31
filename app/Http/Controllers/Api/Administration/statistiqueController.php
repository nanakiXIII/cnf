<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Http\Resources\statistiqueResource;
use App\post;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;


class statistiqueController extends Controller {
    public function index(Request $request){
	    $request->validate(
		    [
			    'start' => 'required|date',
			    'end' => 'required|date',
		    ]
	    );
	    $reponse =  new class{};
	    $premierJour = new Carbon($request->start);
	    $dernierJour = new Carbon($request->end);
	    $periode = Period::create($premierJour, $dernierJour);


	    $userPeriode = Analytics::fetchTotalVisitorsAndPageViews($periode);
	    $tableau = [];
	    foreach ($userPeriode as $d){
		    $tableau['visitors'][] = $d['visitors'];
		    $tableau['date'][] = $d['date']->format('d M');
	    }
	    $pays = Analytics::performQuery($periode, 'ga:sessions',['dimensions'=>'ga:countryIsoCode','sort'=>'-ga:sessions']);
	    $pa = [];
	    foreach ($pays['rows'] as $key => $p){
		    $pa[$p[0]] =  $p[1];
	    }
	    $reponse->premier = $premierJour;
	    $reponse->dernier = $dernierJour;
	    $reponse->pays = $pa;
	    $reponse->navigateur = Analytics::fetchTopBrowsers($periode, 4);
		//$reponse->referents = Analytics::fetchTopReferrers($periode);
	    $reponse->userPeriode = $tableau;
	    $reponse->userType = Analytics::fetchUserTypes($periode);

	    return json_encode($reponse);
    }

    public function show(Request $request){



    }


    public function create(request $request){

    }
    public function update(request $request){

    }
    public function delete(request $request){


    }


}