<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::get('categories',                [CategoryController::class, 'index']);
Route::get('categories/{category}',     [CategoryController::class, 'show']);

Route::apiResource('recipes',           RecipeController::class);
/* Route::get('recipes',                   [RecipeController::class, 'index']);
Route::post('recipes',                  [RecipeController::class, 'store']);
Route::get('recipes/{recipe}',          [RecipeController::class, 'show']);
Route::put('recipes/{recipe}',          [RecipeController::class, 'update']);
Route::delete('recipes/{recipe}',       [RecipeController::class, 'destroy']); */

Route::get('tags',                      [TagController::class, 'index']);
Route::get('tags/{tag}',                [TagController::class, 'show']);