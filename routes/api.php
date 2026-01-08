<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProvinceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/role', [RoleController::class, 'index']);
     Route::post("role", [RoleController::class, 'store']);
    Route::get("role/{id}", [RoleController::class, 'show']);
    Route::put("role/{id}", [RoleController::class, 'update']);
    Route::delete("role/{id}", [RoleController::class, 'destroy']);

    Route::post("role/chagestatus/{id}", [RoleController::class, 'chagestatus']);

    // Route::resource('role', RoleController::class);
    Route::apiResource("categories", CategoryController::class);
    Route::apiResource("province", ProvinceController::class);
    Route::apiResource("brands", BrandController::class);
// });
// Auth
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
