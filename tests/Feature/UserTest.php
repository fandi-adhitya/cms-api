<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
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
        'api/v1/user',
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

    public function test_index_editor_role()
    {
      $user = User::factory()->create();
      $user->syncRoles('editor');

      $response = $this->actingAs($user, 'api')->get(
        'api/v1/user',
        ['Accept' => 'application/json']
      );

      $response->assertStatus(403);
      $response->assertJsonStructure([
        'message',
      ]);
    }

    public function test_index_user_role()
    {
      $user = User::factory()->create();
      $user->syncRoles('user');

      $response = $this->actingAs($user, 'api')->get(
        'api/v1/user',
        ['Accept' => 'application/json']
      );

      $response->assertStatus(403);
      $response->assertJsonStructure([
        'message',
      ]);
    }
}
