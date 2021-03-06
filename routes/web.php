<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Rutas de media tipesDB
Route::get('media-types/insert','MediaTypeController@showmas');
Route::post('media-types/store','MediaTypeController@storemas');

Route::get('masterpage',function(){
    return view('layouts.masterpage');
});
//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Rutas de prefijo: imagen
Route::prefix('imagenes')->group(function(){
    Route::get('crear', 'ImageController@create');
    Route::post('guardar', 'ImageController@store');
});

Route::get('pdf', "PDFController@index" );
