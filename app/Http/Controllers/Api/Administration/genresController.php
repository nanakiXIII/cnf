<?php
namespace App\Http\Controllers\Api\Administration;


use App\Episodes;
use App\Ftp;
use App\Genres;
use App\Http\Controllers\Controller;
use App\Jobs\downloadFile;
use App\Saisons;
use App\Serie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Discord\Discord;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use phpDocumentor\Reflection\Types\Integer;


class genresController extends Controller {
    public function index(Request $request){
        return Genres::orderBy('name', 'asc')->get();
    }

    public function show(Request $request){

    }

    public function create(request $request){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $genre = Genres::create(['name' => $request->name]);
        if ($genre){
            $response['success'] = true;
            $response['data'] = $genre;
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
        $genre = Genres::findOrFail($request->id);
        if ($genre){
            $genre->name = $request->name;
            $genre->save();
            $response['success'] = true;
            $response['data'] = $genre;
        }else{
            $response['error'] = true;
        }

        return response()->json($response);

    }
    public function destroy(request $request, Int $id){
        $response = [
            'statut' => '200',
            'error' => false,
            'success' => false,
            'data' => null
        ];
        $genre = Genres::findOrFail($id);
        if($genre){
            $genre->delete();
            $response['success'] = true;
            $response['data'] = $genre;
        }else{
            $response['error'] = true;
        }
        return response()->json($response);
    }


}