<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

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

/*Route::get('/', function() {
    return view('welcome');
});*/

/*Route::get('/{slug}', function($slug) {
    $post = Post::whereSlug($slug)->first();
    return view('post', ['post' => $post]);
});*/

Route::get('/', [HomeController::class, 'home']);

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register/create', [AuthController::class, 'completeRegistration']);

Route::get('/login', [AuthController::class, 'login']);
Route::post('/login/create', [AuthController::class, 'completeLogin']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/logged', [HomeController::class, 'logged_home']);