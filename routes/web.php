<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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

Route::get('/', function () {
    return view('welcome');
});

route::get('/test',function (){
   return 'welcome to web page' ;
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/chat',[UserController::class, 'chat'])->middleware('auth');

Route::get('/accept/{postId}',[AdminController::class, 'acceptPost'])->name('accept');

Route::get('/delete/{postId}',[AdminController::class, 'deletePost'])->name('delete');

Route::get('/pending',[AdminController::class, 'pending'])->name('pending');

Route::get('/accepted',[AdminController::class, 'accepted'])->name('accepted');

Route::get('/users',[AdminController::class, 'users'])->name('users');

Route::get('/activateUser/{userid}',[AdminController::class, 'activateUser'])->name('activateUser');

Route::get('/notactivateUser/{userid}',[AdminController::class, 'notactivateUser'])->name('notactivateUser');

Route::get('/deleteUser/{userid}',[AdminController::class, 'deleteUser'])->name('deleteUser');
