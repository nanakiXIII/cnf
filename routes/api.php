<?php
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
    //Permission::create(['name' => 'b', 'guard_name' => 'web']);
    //$role = Role::find('1');
    //$role->givePermissionTo('b');
    $user = $request->user();
    $tab = [];
    foreach ($request->user()->roles()->get() as $roles){
        foreach ($roles->permissions()->pluck('name') as $perm){
            if (!in_array($perm, $tab)){
                $tab[] =  $perm;
            }
        }
    }
    $request->user()->role = $request->user()->roles()->pluck('name');
    $request->user()->permission = $tab;
    //$user->assignRole(1);
    //$user->assignRole('writer');
    //$user->assignRole('writer');

    //$roles = $user->roles()->pluck('name');
    //$request->user()->role = $roles;
    //$request->user()->permission = $request->user()->permissions;
    return $request->user();

//->middleware('role:Test')
//->middleware('permission:Administration')
})->middleware('auth:api');

Route::group(['middleware' => ['auth:api'], 'prefix' => 'compte'], function (){
    Route::get('/', 'Api\CompteController@index');
    Route::post('/', 'Api\CompteController@update');
    Route::get('/serie/{type}', 'Api\SerieController@serieAboLog');
    Route::get('/serie/{type}/{slug}', 'Api\SerieController@infoSerie');
    Route::get('/serie/{type}/{slug}/{saison}/{episode}', 'Api\SerieController@infoEpisode');
});
Route::post('/streaming', 'Api\CompteController@update');
Route::get('/projets', 'Api\SerieController@index');
Route::get('/projets/{type}/{slug}', 'Api\SerieController@show');


Route::get('/serie/{type}/{slug}/{saison}/{episode}', 'Api\SerieController@infoEpisode');

Route::get('/news', 'Api\postController@index');
Route::get('/news/{slug}', 'Api\postController@show');

Route::group(['middleware' => ['auth:api', 'permission:Administration'], 'prefix' => 'administration'], function (){
    Route::get('/membres', 'Api\Administration\utilisateurController@index');
    Route::post('/membres', 'Api\Administration\utilisateurController@update');










    Route::post('/series/informations', 'Api\Administration\serieController@informations');


    //Gestion des séries
    Route::post('/series', 'Api\Administration\serieController@create');
    Route::get('/series', 'Api\Administration\serieController@index');
    Route::get('/series/{slug}/{id}', 'Api\Administration\serieController@show');
    Route::put('/series', 'Api\Administration\serieController@update');
    Route::delete('/series/{id}', 'Api\Administration\serieController@delete')->middleware('permission:Supprimer');

    //gestion des saisons
    Route::post('/saison/', 'Api\Administration\SaisonController@create');
    Route::put('/saison/', 'Api\Administration\SaisonController@update');
    Route::delete('/saison/{id}', 'Api\Administration\SaisonController@delete')->middleware('permission:Supprimer');

    //gestion du ftp
    Route::get('/fichier/ftp/update', 'Api\Administration\FichierController@ftpUpdate');
    Route::get('/fichier/ftp', 'Api\Administration\FichierController@index');

    //Gestion des fichier
    Route::post('/fichier', 'Api\Administration\FichierController@create');
    Route::put('/fichier', 'Api\Administration\FichierController@update');
    Route::delete('/fichier/{id}', 'Api\Administration\FichierController@delete')->middleware('permission:Supprimer');
    Route::post('/fichier/archive', 'Api\Administration\FichierController@archive');


    //Gestion des genres
    Route::get('/genres', 'Api\Administration\genresController@index');
    Route::post('/genres', 'Api\Administration\genresController@create');
    Route::put('/genres', 'Api\Administration\genresController@update');
    Route::delete('/genres/destroy/{id}', 'Api\Administration\genresController@destroy')->middleware('permission:Supprimer');

    //GEstion des news
    Route::get('/news', 'Api\Administration\newsController@index');
    Route::get('/news/{id}/{slug}', 'Api\Administration\newsController@show');
    Route::post('/news/image', 'Api\Administration\newsController@image');
    Route::post('/news', 'Api\Administration\newsController@create');
    Route::put('/news', 'Api\Administration\newsController@update');
    Route::delete('/news/{id}', 'Api\Administration\newsController@delete')->middleware('permission:Supprimer');

    //statistiques
    Route::get('/statistique/serie/{id}', 'Api\Administration\serieController@statistique');

    //dashboard
    Route::get('/dashboard', 'Api\Administration\dashboardController@index');
});
