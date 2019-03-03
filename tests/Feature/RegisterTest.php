<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRequiredNameEmailPassword()
    {
        $this->json('POST', 'api/register')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }
    
    public function testRequiredPasswordConfirmation()
    {
        $data = [
            'name' => 'testuser',
            'email' => 'testuser@guestfriend.com',
            'password' => 'guestfriend'
        ];
        
        $this->json('POST', 'api/register', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password confirmation does not match.']
                ]
            ]);
    }
    
    public function testRegisteredSuccessfully()
    {
        $data = [
            'name' => 'testuser',
            'email' => 'testuser@guestfriend.com',
            'password' => 'guestfriend',
            'password_confirmation' => 'guestfriend'
        ];
        
        $this->json('POST', 'api/register', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'api_token',
                ],
            ]);
    }
}
