<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        return Ticket::all();
    }
    
    public function show($id)
    {
        return Ticket::find($id);
    }
    
    public function store(Request $request)
    {
        $ticket = Ticket::create($request->all());
        
        return response()->json($ticket, 201);
    }
    
    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());
        
        return response()->json($ticket, 200);
    }
    
    public function delete(Ticket $ticket)
    {
        $ticket->delete();
        
        return response()->json(null, 204);
    }
}
