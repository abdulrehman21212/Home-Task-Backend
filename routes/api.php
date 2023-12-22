<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/settings',[UserController::class,'updateUserSettings']);
    Route::post('/user/articles',[UserController::class,'getUserArticles']);

});




Route::post('/register',[AuthenticationController::class,'register']);

//ISSUE IN LOGIN , TYPE CORRECT EMAIL , BUT WRONG PASSWORD, NOT GETTING ANYTHING
Route::post('/login',[AuthenticationController::class,'login']);
Route::post('/articles/filter',[ArticleController::class,'filterArticles']);


Route::get('/get/authors',[ArticleController::class,'getAuthors']);
Route::get('/get/categories',[ArticleController::class,'getCategories']);
Route::get('/get/sources',[ArticleController::class,'getSources']);
Route::post('/get/articles',[ArticleController::class,'getArticles']);






