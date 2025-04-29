<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Support\Facades\Gate;

use App\Http\Resources\RecipeResource;

use Symfony\Component\HttpFoundation\Response;

use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index() {
        $recipes = Recipe::with('category', 'tags', 'user')->get();
        // all, get
        return RecipeResource::collection($recipes);
    }

    public function store(StoreRecipeRequest $request) {

        $recipe = $request->user()->recipes()->create($request->all());
        $recipe->tags()->attach(json_decode($request->input('tags')));

        $recipe->image = $request->file('image')->store('recipes', 'public');
        $recipe->save();

        return response()->json(new RecipeResource($recipe), Response::HTTP_CREATED);  // HTTP 201
    }

    public function show(Recipe $recipe) {
        $recipe = $recipe->load('category', 'tags', 'user');

        return new RecipeResource($recipe);
    }

    public function update(Recipe $recipe, UpdateRecipeRequest $request) {
        Gate::authorize('update', $recipe);

        $recipe->update($request->all());

        if ($tags = json_decode($request->input('tags'))) {
            $recipe->tags()->sync($tags);
        }

        if ($request->hasFile('image')) {
            $recipe->image = $request->file('image')->store('recipes', 'public');
            $recipe->save();
        }

        return response()->json(new RecipeResource($recipe), Response::HTTP_OK); // 200
    }

    public function destroy(Recipe $recipe) {
        Gate::authorize('delete', $recipe);

        $recipe->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }
}
