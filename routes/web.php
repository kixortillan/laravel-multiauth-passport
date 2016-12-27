<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/oauth/{provider}', 'Auth\SocialiteAuthController@redirectToProvider');
Route::get('/oauth/{provider}/redirect', 'Auth\SocialiteAuthController@handleProviderCallback');

Route::get('/testauth', function(Request $request){
    $http = new GuzzleHttp\Client([
        'base_uri' => 'http://local.talaoauth'
    ]);

    $response = $http->post('/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '4',
            'client_secret' => 'N6gaHuVDkPyMVBWMtsATFCHQz7hJb1iQlY0KF06V',
            'redirect_uri' => 'http://localhost:8000/testauth',
            'code' => $request->query('code'),
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('/register/verify', 'Auth\RegisterController@verify');