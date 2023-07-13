<?php

use Illuminate\Http\Request;
use App\Http\Controllers\MediaInfractorController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::get('/mediaInfractor/create', [MediaInfractorController::class, 'create'])->name('mediaInfractor.create');
Route::post('/mediaInfractor/store', [MediaInfractorController::class, 'store'])->name('mediaInfractor.store');
Route::get('/mediaInfractor', [MediaInfractorController::class, 'index'])->name('mediaInfractor.index');
Route::post('/mediaInfractor/identifyUser', [MediaInfractorController::class, 'identifyUser'])->name('mediaInfractor.identifyUser');
