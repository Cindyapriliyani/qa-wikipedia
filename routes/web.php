<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WikipediaQAController;
use App\Http\Controllers\QuestionAnsweringController;
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
    return view('home');
});

Route::post('/ask-question', 'QuestionAnsweringController@askQuestion');

Route::get('/qa', [WikipediaQAController::class, 'index']);
Route::post('/qa', [WikipediaQAController::class, 'process']);


Route::post('/qa/answer', [QuestionAnsweringController::class, 'answer']);



// Route::get('home', [HomeController::class, 'index'])->name('home');