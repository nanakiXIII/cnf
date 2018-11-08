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


    public function index(){
       $post = post::where('publication', true)->orderBy('publish_at', 'DESC')->paginate(10);
       $news = [];
       foreach ($post as $p){
           $news['data'][$p->id] = $p;
       }
       $news['pagination'] =
           ['total' => $post->total(),

               'per_page' => $post->perPage(),

               'current_page' => $post->currentPage(),

               'last_page' => $post->lastPage(),

               'from' => $post->firstItem(),

               'to' => $post->lastItem()];
       return $news;

    }



}