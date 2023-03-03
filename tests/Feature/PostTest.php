<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class PostTest extends TestCase
{
  use HasRoles, WithFaker;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_index_admin_role()
  {
    $user = User::factory()->create();
    $user->syncRoles('admin');

    $response = $this->actingAs($user, 'api')->get(
      'api/v1/post',
      ['Accept' => 'application/json']
    );

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        '*' => [
          'id',
          'title',
          'content',
          'created_at',
          'updated_at',
        ],
      ],
      'meta' => [
        'total',
        'perPage',
        'currentPage',
        'lastPage',
      ],
    ]);
  }


  public function test_index_editor_role()
  {
    $user = User::factory()->create();
    $user->syncRoles('editor');

    $response = $this->actingAs($user, 'api')->get(
      'api/v1/post',
      ['Accept' => 'application/json']
    );

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        '*' => [
          'id',
          'title',
          'content',
          'created_at',
          'updated_at',
        ],
      ],
      'meta' => [
        'total',
        'perPage',
        'currentPage',
        'lastPage',
      ],
    ]);
  }

  public function test_index_user_role()
  {
    $user = User::factory()->create();
    $user->syncRoles('user');

    $response = $this->actingAs($user, 'api')->get(
      'api/v1/post',
      ['Accept' => 'application/json']
    );

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        '*' => [
          'id',
          'name',
          'email',
          'role',
          'created_at',
          'updated_at',
        ],
      ],
      'meta' => [
        'total',
        'perPage',
        'currentPage',
        'lastPage',
      ],
    ]);
  }

  public function test_store()
  {
    $user = User::factory()->create();
    $user->syncRoles('admin');

    $response = $this->actingAs($user, 'api')->post(
      'api/v1/post',
      [
        'title' => $this->faker->text(20),
        'content' => $this->faker->text(50),
      ],
      ['Accept' => 'application/json']
    );

    $response->assertStatus(201);
  }
}
