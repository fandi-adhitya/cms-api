<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authentication()
    {
        $response = $this->post(
          'api/v1/auth', 
          [
            'email' => 'admin@example.com',
            'password' => 'password'
          ], 
          [
            'Accept' => 'application/json'
          ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
          'user',
          'accessToken',
          'expiredAt'
        ]);
    }

    public function test_authentication_fail(){
      $response = $this->post(
        'api/v1/auth', 
        [
          'email' => 'password@example.com',
          'password' => 'password'
        ], 
        [
          'Accept' => 'application/json'
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonStructure([
          'message',
        ]);

    }
}
