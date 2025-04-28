<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;

use App\Http\Resources\RecipeResource;

class RecipeController extends Controller
{
    public function index() {
        // all, get
        return Recipe::with('category', 'tags', 'user')->get();
    }

    public function store(Request $request) {
        $recipe = Recipe::create($request->all());
        return response()->json($recipe, 201);
    }

    public function show(Recipe $recipe) {
        return $recipe->load('category', 'tags', 'user');
    }

    public function update() {}

    public function destroy() {}
}
