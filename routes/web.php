<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/mediaInfractor/create',[App\Http\Controllers\MediaInfractorController::class, 'create'])->name('mediaInfractor.create');
Route::post('/mediaInfractor/store',[App\Http\Controllers\MediaInfractorController::class, 'store'])->name('mediaInfractor.store');

Route::get('/mediaInfractor/identifyUser',[App\Http\Controllers\MediaInfractorController::class, 'identify'])->name('mediaInfractor.identify');
Route::post('/mediaInfractor/identifyUser',[App\Http\Controllers\MediaInfractorController::class, 'identifyUser'])->name('mediaInfractor.identifyUser');
