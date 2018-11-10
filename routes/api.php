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
    //$role = Role::find('2');
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
    //$user->assignRole(2);
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
Route::get('/serie/{type}', 'Api\SerieController@serieAbo');
Route::get('/serie/{type}/{slug}', 'Api\SerieController@infoSerie');
Route::get('/serie/{type}/{slug}/{saison}/{episode}', 'Api\SerieController@infoEpisode');

Route::get('/news', 'Api\postController@index');
Route::get('/news/{slug}', 'Api\postController@show');

Route::group(['middleware' => ['auth:api', 'permission:Administration'], 'prefix' => 'administration'], function (){
    Route::get('/membres', 'Api\Administration\utilisateurController@index');

});
