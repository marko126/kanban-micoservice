<?php

namespace Tests\Feature\Feature;

use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{  
    public function testLoginSuccessfully()
    {
        $user = factory(User::create([
            'name' => 'testuser',
            'email' => 'testuser@guestfriend.com',
            'password' => bcrypt('guestfriend')
        ]));
        
        $user = User::where('email', 'testuser@guestfriend.com')->orderBy('id', 'desc')->first();
        $user->generateToken();
        
        $data = [
            'email' => 'testuser@guestfriend.com',
            'password' => 'guestfriend',
            'api_token' => $user->api_token
        ];
        
        $this->json('POST', 'api/login', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'api_token',
                ],
            ]);
    }
    
    public function testRequiredEmailAndPassword()
    {     
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }
    
    public function testWrongEmailOrPassword()
    {
        $data = [
            'email' => 'fakeuser@fakesite.com',
            'password' => '25423434t3sdfvsdfgt45evdfvf'
        ];
        
        $this->json('POST', 'api/login', $data)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['These credentials do not match our records.']
                ]
            ]);
    }
}
