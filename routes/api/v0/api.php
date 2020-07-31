<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('passwords', function () {
    $users = \App\User::orderBy('id')->get();
    $passwords = array();
    $users2 = array();
    foreach ($users as $user) {
        $passwords[] = \Illuminate\Support\Facades\Hash::make($user->identification);
        $users2[] = $user->first_lastname;
    }
    return response()->json(['users' => $users2, 'passwords' => $passwords]);
});

// Users
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'v0\AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'v0\AuthController@logout');
        Route::get('user', 'v0\AuthController@user');
        Route::put('users', 'v0\AuthController@updateUser');
        Route::put('password', 'v0\AuthController@changePassword');
        Route::post('users/avatar', 'v0\AuthController@uploadAvatarUri');
    });
});

/**********************************************************************************************************************/

/* Rutas para registar usuarios (Profesionales y Empresas)*/
Route::group(['prefix' => 'users'], function () {
    Route::post('login', 'v0\UserController@login');
    Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'v0\UserController@logout');
    Route::post('createCompanyUser',  'v0\UserController@createCompanyUser');
    Route::post('createProfessionalUser', 'v0\UserController@createProfessionalUser');
});
});
Route::group(['prefix'=> 'professionals'],function(){
    // Route::group(['middleware'=> 'auth:api'],function(){
        Route::get('/abilities', 'v0\ProfessionalController@getAbilities');
        Route::get('/academicFormations', 'v0\ProfessionalController@getAcademicFormations');
        Route::get('/courses', 'v0\ProfessionalController@getCourses');
        Route::get('/languages', 'ProfessionalController@getLanguages');
        Route::get('/professionalExperiences', 'v0\ProfessionalController@getProfessionalExperiences');
        Route::get('/professionalReferences', 'v0\ProfessionalController@getProfessionalReferences');

        Route::get('/offers', 'v0\ProfessionalController@getAppliedOffers');
        Route::post('/offers/filter', 'v0\ProfessionalController@filterOffers');
        Route::post('/offers', 'v0\ProfessionalController@createOffer');
        Route::get('/companies', 'ProfessionalController@getAppliedCompanies');

        Route::get('/{id}', 'v0\ProfessionalController@showProfessional');
        Route::post('', 'v0\ProfessionalController@createProfessional');
        Route::put('', 'v0\ProfessionalController@updateProfessional');
        Route::delete('', 'v0\ProfessionalController@deleteProfessional');

        Route::get('/all','v0\ProfessionalController@getAllProfessionals');
    // });

});