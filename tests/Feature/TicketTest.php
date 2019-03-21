<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Ticket;

class TicketTest extends TestCase
{
    public function testTicketsAreListedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $user = User::find($user->id);
        
        factory(Ticket::class)->create([
            'title' => 'Test ticket #1',
            'description' => 'This is a test ticket #1',
            'status' => Ticket::STATUS_TO_DO,
            'priority' => 1,
            'user_id' => $user->id
        ]);

        factory(Ticket::class)->create([
            'title' => 'Test ticket #2',
            'description' => 'This is a test ticket #2',
            'status' => Ticket::STATUS_IN_PROGRESS,
            'priority' => 2,
            'user_id' => $user->id
        ]);

        $this->json('GET', '/api/tickets', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                [
                    'title' => 'Test ticket #1',
                    'description' => 'This is a test ticket #1',
                    'status' => Ticket::STATUS_TO_DO,
                    'priority' => 1,
                    'user_id' => $user->id
                ],
                [
                    'title' => 'Test ticket #2',
                    'description' => 'This is a test ticket #2',
                    'status' => Ticket::STATUS_IN_PROGRESS,
                    'priority' => 2,
                    'user_id' => $user->id
                ]
            ])
            ->assertJsonStructure([
                '*' => ['id', 'title', 'description', 'status', 'priority', 'user_id', 'created_at', 'updated_at'],
            ]);
    }
    
    public function testsTicketIsCreatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $data = [
            'title' => 'Test ticket',
            'description' => 'This is a test ticket',
            'status' => Ticket::STATUS_TO_DO,
            'user_id' => $user->id
        ];

        $this->json('POST', '/api/tickets/create', $data, $headers)
            ->assertStatus(201)
            ->assertJson([
                'title' => 'Test ticket',
                'description' => 'This is a test ticket',
                'status' => Ticket::STATUS_TO_DO,
                'user_id' => $user->id
            ]);
    }
    
    public function testsTicketIsUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $ticket = factory(Ticket::class)->create([
            'title' => 'Test ticket',
            'description' => 'This is a test ticket',
            'status' => Ticket::STATUS_TO_DO,
            'priority' => 1,
            'user_id' => $user->id
        ]);

        $data = [
            'title' => 'Test ticket updated',
            'description' => 'This is a test ticket updated',
        ];

        $this->json('PUT', '/api/tickets/update/' . $ticket->id, $data, $headers)
            ->assertStatus(200)
            ->assertJson([ 
                'id' => 1, 
                'title' => 'Test ticket updated',
                'description' => 'This is a test ticket updated', 
            ]);
    }
    
    public function testsTicketIsPriorityUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $ticket1 = factory(Ticket::class)->create([
            'title' => 'Test ticket #1',
            'description' => 'This is a test ticket #1',
            'status' => Ticket::STATUS_TO_DO,
            'priority' => 1,
            'user_id' => $user->id
        ]);
        
        $ticket2 = factory(Ticket::class)->create([
            'title' => 'Test ticket #2',
            'description' => 'This is a test ticket #2',
            'status' => Ticket::STATUS_TO_DO,
            'priority' => 2,
            'user_id' => $user->id
        ]);
        
        $ticket3 = factory(Ticket::class)->create([
            'title' => 'Test ticket #3',
            'description' => 'This is a test ticket #3',
            'status' => Ticket::STATUS_TO_DO,
            'priority' => 3,
            'user_id' => $user->id
        ]);

        $data = [
            'priority' => 2
        ];

        $this->json('PUT', '/api/tickets/updatepriority/' . $ticket3->id, $data, $headers)
            ->assertStatus(200)
            ->assertJson([ 
                'title' => 'Test ticket #3',
                'description' => 'This is a test ticket #3',
                'status' => 1,
                'priority' => 2, 
            ]);
    }
    
    public function testsTicketIsDeletedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $ticket = factory(Ticket::class)->create([
            'title' => 'Test ticket',
            'description' => 'This is a test ticket',
            'status' => Ticket::STATUS_DONE,
            'priority' => 1,
            'user_id' => $user->id
        ]);

        $this->json('DELETE', '/api/tickets/delete/' . $ticket->id, [], $headers)
            ->assertStatus(204);
    }
    
    public function testsTicketInvalidateData()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        
        $data = [
            'title' => 'Test',
            'description' => 'This is a test ticket',
            'status' => 0,
            'user_id' => $user->id
        ];

        $this->json('POST', '/api/tickets/create', $data, $headers)
            ->assertStatus(200)
            ->assertJson([
                'title' => [
                    "The title must be at least 5 characters."
                ],
                'status' => [
                    "The selected status is invalid."
                ]
            ]);
    }
}
