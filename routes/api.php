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

Route::get('/user', 'Api\userController@show')->middleware('auth:api');
Route::post('/user/avatar', 'Api\CompteController@avatar')->middleware('auth:api');
Route::put('/user', 'Api\CompteController@update')->middleware('auth:api');

Route::group(['middleware' => ['auth:api'], 'prefix' => 'compte'], function (){
    Route::get('/', 'Api\CompteController@index');
    Route::post('/', 'Api\CompteController@update');
    Route::get('/serie/{type}', 'Api\SerieController@serieAboLog');
    Route::get('/serie/{type}/{slug}', 'Api\SerieController@infoSerie');
    Route::get('/serie/{type}/{slug}/{saison}/{episode}', 'Api\SerieController@infoEpisode');
    // Gestions des abonnements
    Route::post('/abonnement', 'Api\CompteController@abonnement');
});
//commentaires
Route::post('/commentaires', 'Api\postController@comments')->middleware('auth:api');
// Téléchargements
Route::post('/telechargements', 'Api\SerieController@telechargement');

Route::post('/streaming', 'Api\CompteController@update');
Route::get('/projets', 'Api\SerieController@index');
Route::get('/seedteam', 'Api\SerieController@seedteam');
Route::get('/projets/{type}/{slug}', 'Api\SerieController@show');
//equipes
Route::get('/equipe', 'Api\staffController@index');
//Avancements
Route::get('/avancements', 'Api\avancementsController@index');


Route::get('/serie/{type}/{slug}/{saison}/{episode}', 'Api\SerieController@infoEpisode');

Route::get('/news', 'Api\postController@index');
Route::get('/news/{slug}', 'Api\postController@show');

Route::group(['middleware' => ['auth:api', 'permission:Administration'], 'prefix' => 'administration'], function (){
    // Gestion des membres
    Route::get('/membres', 'Api\Administration\utilisateurController@index')->middleware('permission:GestionDesUsers');
    Route::put('/membres', 'Api\Administration\utilisateurController@update')->middleware('permission:GestionDesUsers');
    //Gestion des postes
    Route::get('/postes', 'Api\Administration\PostesController@index')->middleware('permission:GestionDesUsers');
    Route::post('/postes', 'Api\Administration\PostesController@create')->middleware('permission:GestionDesUsers');
    Route::put('/postes', 'Api\Administration\PostesController@update')->middleware('permission:GestionDesUsers');
    Route::delete('/postes/{id}', 'Api\Administration\PostesController@delete')->middleware('permission:Supprimer');
    //Gestion des permissions
    Route::get('/permissions', 'Api\Administration\PermissionController@index')->middleware('permission:Gestion');
    Route::post('/permissions', 'Api\Administration\PermissionController@create')->middleware('permission:Gestion');
    Route::put('/permissions', 'Api\Administration\PermissionController@update')->middleware('permission:Gestion');
    Route::delete('/permissions/{id}', 'Api\Administration\PermissionController@delete')->middleware('permission:Supprimer');
    //Gestion des Grades
    Route::get('/grades', 'Api\Administration\GradesController@index')->middleware('permission:GestionDesUsers');
    Route::post('/grades', 'Api\Administration\GradesController@create')->middleware('permission:GestionDesUsers');
    Route::put('/grades', 'Api\Administration\GradesController@update')->middleware('permission:GestionDesUsers');
    Route::delete('/grades/{id}', 'Api\Administration\GradesController@delete')->middleware('permission:Supprimer');
    //Gestion des commentaires
    Route::get('/commentaires', 'Api\Administration\CommentsController@index')->middleware('permission:GestionDesCommentaires');
    Route::delete('/commentaires/{id}', 'Api\Administration\CommentsController@delete')->middleware('permission:Supprimer');

    Route::post('/series/informations', 'Api\Administration\serieController@informations');

    //Gestion des séries
    Route::post('/series', 'Api\Administration\serieController@create')->middleware('permission:GestionDesSeries');
    Route::get('/series', 'Api\Administration\serieController@index')->middleware('permission:GestionDesSeries');
    Route::get('/series/{slug}/{id}', 'Api\Administration\serieController@show')->middleware('permission:GestionDesSeries');
    Route::put('/series', 'Api\Administration\serieController@update')->middleware('permission:GestionDesSeries');
    Route::delete('/series/{id}', 'Api\Administration\serieController@delete')->middleware('permission:Supprimer');

    //gestion des saisons
    Route::post('/saison/', 'Api\Administration\SaisonController@create')->middleware('permission:GestionDesSeries');
    Route::put('/saison/', 'Api\Administration\SaisonController@update')->middleware('permission:GestionDesSeries');
    Route::delete('/saison/{id}', 'Api\Administration\SaisonController@delete')->middleware('permission:Supprimer');

    //gestion du ftp
    Route::get('/fichier/ftp/update', 'Api\Administration\FichierController@ftpUpdate')->middleware('permission:GestionDesSeries');
    Route::get('/fichier/ftp', 'Api\Administration\FichierController@index')->middleware('permission:GestionDesSeries');

    //Gestion des fichier
    Route::post('/fichier', 'Api\Administration\FichierController@create')->middleware('permission:GestionDesFichiers');
    Route::put('/fichier', 'Api\Administration\FichierController@update')->middleware('permission:GestionDesFichiers');
    Route::delete('/fichier/{id}', 'Api\Administration\FichierController@delete')->middleware('permission:Supprimer');
    Route::post('/fichier/archive', 'Api\Administration\FichierController@archive')->middleware('permission:GestionDesFichiers');


    //Gestion des genres
    Route::get('/genres', 'Api\Administration\genresController@index')->middleware('permission:GestionDesGenres');
    Route::post('/genres', 'Api\Administration\genresController@create')->middleware('permission:GestionDesGenres');
    Route::put('/genres', 'Api\Administration\genresController@update')->middleware('permission:GestionDesGenres');
    Route::delete('/genres/destroy/{id}', 'Api\Administration\genresController@destroy')->middleware('permission:Supprimer');

    //GEstion des news
    Route::get('/news', 'Api\Administration\newsController@index')->middleware('permission:GestionDesNews');
    Route::get('/news/{id}/{slug}', 'Api\Administration\newsController@show')->middleware('permission:GestionDesNews');
    Route::post('/news/image', 'Api\Administration\newsController@image')->middleware('permission:GestionDesNews');
    Route::post('/news', 'Api\Administration\newsController@create')->middleware('permission:GestionDesNews');
    Route::put('/news', 'Api\Administration\newsController@update')->middleware('permission:GestionDesNews');
    Route::delete('/news/{id}', 'Api\Administration\newsController@delete')->middleware('permission:Supprimer');

    //statistiques
    Route::get('/statistique/serie/{id}', 'Api\Administration\serieController@statistique');
    Route::post('/statistique/visites', 'Api\Administration\statistiqueController@index');

    //dashboard
    Route::get('/dashboard', 'Api\Administration\dashboardController@index')->middleware('permission:Administration');
    Route::get('/dashboard/statistiques', 'Api\Administration\dashboardController@show')->middleware('permission:Administration');

    //avancements
    Route::get('/avancements', 'Api\Administration\avancementsController@index')->middleware('permission:GestionDesAvancements');
    Route::post('/avancements', 'Api\Administration\avancementsController@create')->middleware('permission:GestionDesAvancements');
    Route::put('/avancements', 'Api\Administration\avancementsController@update')->middleware('permission:GestionDesAvancements');
    Route::delete('/avancements/{id}', 'Api\Administration\avancementsController@delete')->middleware('permission:Supprimer');

    //gestion des fichiers
    Route::get('/gestion/fichiers', 'Api\Administration\gestionController@index')->middleware('permission:Gestion');
    Route::put('/gestion/fichiers', 'Api\Administration\gestionController@update')->middleware('permission:Gestion');

});
