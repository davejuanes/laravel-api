<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;

class RecipePolicy
{
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id; // true or false
    }

    public function delete(User $user, Recipe $recipe)
    {
        return $user->id === $recipe->user_id;
    }
}
