<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Http\Controllers\Controller;
use App\post;
use App\Repository\AccountRepository;
use App\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class postController extends Controller {


    public function index(Request $request){
       $post = post::where('publication', true)->orderBy('publish_at', 'DESC')->paginate(10);
       $news = [];
       $cpt=$request->from;
       foreach ($post as $p){
           $news['data'][$cpt] = $p;
           $cpt++;
       }
       $news['pagination'] =
           [
               'total' => $post->total(),

               'per_page' => $post->perPage(),

               'current_page' => $post->currentPage(),

               'last_page' => $post->lastPage(),

               'from' => $post->firstItem(),

               'to' => $post->lastItem()
           ];
       return $news;

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