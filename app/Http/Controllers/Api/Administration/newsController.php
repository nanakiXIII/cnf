<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Http\Resources\NewsAdminCollection;
use App\Http\Resources\NewsAdminResource;
use App\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;
use phpDocumentor\Reflection\Types\Null_;


class newsController extends Controller {
    public function index(Request $request){
        $post = post::orderBy('publication', 'ASC')->orderBy('publish_at', 'DESC')->get();
        return new NewsAdminCollection($post);

    }
    public function image(Request $request){
        $response = [];
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/images/news', $imageName);
            $response['status'] = 200;
            $response['url'] = "/storage/images/news/$imageName";
        }
        return $response;

    }

    public function show(Request $request){
        $post=  post::where('id', $request->id)->where('slug', $request->slug)->firstOrFail();
        return new NewsAdminResource($post);
    }


    public function create(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $news = post::create([  'titre' => $request->titre,
                                'contenu' => $request->contenu,
                                'publication' => $request->publication,
                                'staff' => $request->staff,
                                'type' => $request->type,
                                'serie_id' => $request->serie_id,
                                'image' => $request->image,
                                'slug' => str_slug($request->titre),
                                'user_id' => $request->user()->id,
                                'etat' => 0
            ]);
        if ($news){
            if ($news->publication == 1){
                $array = [ 'mention_everyone' => true, 'content' => "@everyone $news->titre" ,"embed" =>['title'=>"$news->titre",
                    'type' => 'article',
                    'description' => mb_strimwidth(strip_tags($news->contenu), 0, 300, "..."),
                    'url' => env('APP_URL').'/news/'.$news->slug,
                    'image'=>['url' => env('APP_URL').$news->image]]];
                $channel = app(Discord::class)->send(env('News'), $array );
                $news->publish_at = now();
                $news->etat = 1;
                $news->save();
            }
            $news->fichiers()->sync($request->fichiers);
            $response['success'] = true;
            $response['data'] = $news;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);

    }
    public function update(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $post = post::findOrFail($request->id);
        if ($post){
           $post->titre = $request->titre;
           $post->contenu = $request->contenu;
           $post->image = $request->image;
           $post->type = $request->type;
           $post->serie_id = $request->serie_id;
           $post->publication = $request->publication;
           $post->staff = $request->staff;
           if ($post->publication == 1){
               if ($post->etat == 0){
                   $array = ['mention_everyone' => true, 'content' => "@everyone $post->titre", "embed" =>['title'=>"$post->titre",
                        'type' => 'article',
                       'description' => mb_strimwidth(strip_tags($post->contenu), 0, 300, "..."),
                       'url' => env('APP_URL').'/news/'.$post->slug,
                       'image'=>['url' => env('APP_URL').$post->image]]];
                   $channel = app(Discord::class)->send(env('News'), $array );
                   $post->publish_at = now();
                   $post->etat = 1;
               }
           }
            $post->save();
           $post->fichiers()->sync($request->fichier_id);
            $response['success'] = true;
            $response['data'] = $post;
        }else{
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
        $post = post::findOrFail($request->id);
        if($post){
            if ($post->image != Null){
                $image = str_replace('/storage/','' ,$post->image);
                Storage::disk('public')->delete($image);
            }

            $post->delete();
            $response['success'] = true;
            $response['data'] = $image;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);

    }


}