<?php
use Illuminate\Http\Request;
use \App\Serie;
use \App\Http\Resources\SerieResource;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();

})->middleware('auth:api');

Route::group(['middleware' => ['auth:api'], 'prefix' => 'compte'], function (){
    Route::get('/', 'Api\CompteController@index');
    Route::get('/serie/{type}', 'Api\SerieController@serieAboLog');
    Route::get('/serie/{type}/{slug}', 'Api\SerieController@infoSerie');
});

Route::get('/serie/{type}', 'Api\SerieController@serieAbo');
Route::get('/serie/{type}/{slug}', 'Api\SerieController@infoSerie');
