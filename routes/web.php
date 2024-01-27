<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});


//testing route
Route::get('/index', function () {
    return view('index');
}); 
//testing route
Route::get('/user', function () {
    return view('users/usermanagements/create');
});
//testing route
Route::get('/profile', function () {
    return view('users/profile/index');
});
