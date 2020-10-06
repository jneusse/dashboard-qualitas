<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
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

Route::post('/authenticate/login', 'Auth\LoginController@login');
Route::group(['middleware' => ['auth']], function () {
    // Authentication
    Route::post('/authenticate/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/user/getAuthenticateUser', function () {
        return response()->json([
            'user' => Auth::user(),
            'code' => 200
            ]);
        });
    // Admin Users
    Route::get('/admin/users', 'Auth\Administration\UsersController@pagination');
    Route::get('/admin/users/search', 'Auth\Administration\UsersController@search');
    Route::post('/admin/users/delete', 'Auth\Administration\UsersController@destroy');
    Route::post('/admin/users/edit', 'Auth\Administration\UsersController@update');
    Route::post('/admin/users/create', 'Auth\Administration\UsersController@store');
});

Route::get('/{opcional?}', function () {
    return view('app');
})->name('basepath')
    ->where('opcional', '.*');
