<?php

use App\Models\Padlet;
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


//Route::get('/', function () {
//    $padlets = Padlet::all();
//    return $padlets;
//});



Route::get('/', function () {
    $padlet = App\Models\Padlet::find(2);
    return view('welcome', compact('padlet'));
});



//Route::get('/', function () {
//    $padlets = DB::table('padlets')->get();
//    return $padlets;
//});


//Route::get('/entries', function () {
//    $entries = DB::table('entries')->get();
//    return view('welcome', compact('entries'));
//});
//
//Route::get('/entries/{id}', function ($id){
//    $entry = DB::table('entries')->find($id);
//    dd($entry);
//});
