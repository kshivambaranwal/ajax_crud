<?php

use App\Http\Controllers\api\CrudController;
use Illuminate\Http\Request;
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

Route::get('/index',[CrudController::class,'index'])->name('index');
Route::get('/fetch-all',[CrudController::class,'fetchall'])->name('fetch_all');
Route::post('/user-delete',[CrudController::class,'delete'])->name('user_delete');
Route::post('/user-store',[CrudController::class,'store'])->name('user_store');
Route::post('/user-update',[CrudController::class,'update'])->name('user_update');
