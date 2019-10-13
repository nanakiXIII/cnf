<?php
namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Episodes;
use App\Http\Controllers\Controller;
use App\Http\Resources\commentsResource;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
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
       return new NewsCollection($post);

    }
    public function show(Request $request){
        $response = new class{};
        $post = post::where('publication', true)->where('slug', $request->slug)->first();
        //$comment = $post->commentAsUser(Auth::user(), "commentaire");
        //$comment->approve();
        return NewsResource::make($post);
    }
    public function comments(Request $request){
        $request->validate(
            [
                'id' => 'required|integer',
                'commentaire' => 'required|string',
                'captcha' => 'required|bool',
            ]
        );
        $news = post::findOrFail($request->id);
        if ($news){
            $comment = $news->commentAsUser(Auth::user(), $request->commentaire);
            $comment->approve();
        }
        return commentsResource::make($comment);
    }



}