<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;

use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

class RecipeTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Category::factory()->create();

        $recipes = Recipe::factory(2)->create();

        $response = $this->getJson('/api/v1/recipes');

        $response->assertStatus(Response::HTTP_OK) // 200
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'attributes' => [
                            'title',
                            'description',
                            'instructions',
                        ]
                    ]
                ],
            ]);
    }

    public function test_store(): void
    {
        Sanctum::actingAs(User::factory()->create()); 

        $category   = Category::factory()->create();
        $tag        = Tag::factory()->create();

        $data = [
            'category_id'       => $category->id,
            'title'             => $this->faker->sentence,
            'description'       => $this->faker->paragraph,
            'ingredients'       => $this->faker->text,
            'instructions'      => $this->faker->text,
            'tags'              => $tag->id,
            'image'             => UploadedFile::fake()->image('recipe.png')
        ];

        $response = $this->postJson('/api/v1/recipes/', $data);
        $response->assertStatus(Response::HTTP_CREATED); // 200
    }
    
    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        $recipe = Recipe::factory()->create();

        $response = $this->getJson('/api/v1/recipes/' . $recipe->id);

        $response->assertStatus(Response::HTTP_OK) // 200
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => [
                        'title',
                        'description',
                        'instructions',
                    ]
                ],
            ]);
    }

    public function test_update(): void
    {
        Sanctum::actingAs(User::factory()->create()); 

        $category   = Category::factory()->create();
        $recipe     = Recipe::factory()->create();

        $data = [
            'category_id'       => $category->id,
            'title'             => 'Updated title',
            'description'       => 'Updated description',
            'ingredients'       => $this->faker->text,
            'instructions'      => $this->faker->text,
        ];

        $response = $this->putJson('/api/v1/recipes/' . $recipe->id, $data);
        $response->assertStatus(Response::HTTP_OK); // 200

        $this->assertDatabaseHas('recipes', [
            'title'             => 'Updated title',
            'description'       => 'Updated description',
        ]);
    }

    public function test_destroy(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->create();

        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson('/api/v1/recipes/' . $recipe->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT); // 200
    }
}
