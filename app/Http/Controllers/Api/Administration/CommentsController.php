<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\Http\Resources\commentAdminResource;
use App\Http\Resources\commentsResource;
use App\Http\Resources\NewsAdminCollection;
use App\Http\Resources\NewsAdminResource;
use App\post;
use App\postes;
use BeyondCode\Comments\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;
use phpDocumentor\Reflection\Types\Null_;


class CommentsController extends Controller {
    public function index(){
        $comments =  Comment::orderBy('id', 'DESC')->get();
        return commentAdminResource::collection($comments);
    }

    public function delete(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $poste = Comment::findOrFail($request->id);
        if($poste){
            $poste->delete();
            $response['success'] = true;
            $response['data'] = $poste;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);

    }


}