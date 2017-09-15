<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//login
Route::post('/login','ApiController@login');

//register
Route::post('/register','ApiController@register');

//send money
Route::post('/send/{person_id}','UserController@send');

//buy airtime own
Route::post('/buyAirtime/{person_id}','UserController@buyAirtime');

//buy airtime others
Route::post('/buyAirtimeOther/{person_id}','UserController@buyAirtimeOther');