<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    public function testUsersAreListedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $user = User::find($user->id);
        
        $users = [];
        for ($i = 1; $i < 10; $i ++) {
            $users[$i] = [
                'name' => 'Test User #' . $i,
                'email' => "testuser{$i}@testsite.com",
                'email_verified_at' => now(),
                'password' => 'guestfriend',
                'remember_token' => Str::random(10),
            ];
            // Lets create the user
            factory(User::class)->create($users[$i]);
            // We don't need password and remember token in response
            unset($users[$i]['password']);
            unset($users[$i]['remember_token']);
        }

        $this->json('GET', '/api/users', [], $headers)
            ->assertStatus(200)
            ->assertJson($users)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at', 'api_token'],
            ]);
    }
    
    public function testsUserIsCreatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $data = [
            'name' => 'Test User',
            'email' => "testuser@testsite.com",
            'password' => 'guestfriend',
            'remember_token' => Str::random(10),
        ];

        $this->json('POST', '/api/users/create', $data, $headers)
            ->assertStatus(201)
            ->assertJson([
                'name' => 'Test User',
                'email' => "testuser@testsite.com"
            ]);
    }
    
    public function testsUserIsUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $userTest = factory(User::class)->create([
            'name' => 'Test User',
            'email' => "testuser@testsite.com",
            'password' => 'guestfriend',
            'remember_token' => Str::random(10),
        ]);

        $data = [
            'name' => 'Test User updated'
        ];

        $this->json('PUT', '/api/users/update/' . $userTest->id, $data, $headers)
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Test User updated'
            ]);
    }
    
    public function testsUserIsDeletedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $userTest = factory(User::class)->create([
            'name' => 'Test User',
            'email' => "testuser@testsite.com",
            'password' => 'guestfriend',
            'remember_token' => Str::random(10),
        ]);

        $this->json('DELETE', '/api/users/delete/' . $userTest->id, [], $headers)
            ->assertStatus(204);
    }
    
    public function testsUserInvalidateData()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $data = [
            'name' => 'T',
            'email' => "testuser",
            'password' => 'guestfriend',
            'remember_token' => Str::random(10),
        ];

        $this->json('POST', '/api/users/create', $data, $headers)
            ->assertStatus(200)
            ->assertJson([
                "name" => [
                    "The name must be at least 2 characters."
                ],
                "email" => [
                    "The email must be a valid email address."
                ]
            ]);
    }
}
