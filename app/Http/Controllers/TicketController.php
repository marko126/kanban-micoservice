<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::guard()->user();
        
        return Ticket::where('user_id', $user->id)->get();
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
    
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());
        
        return response()->json($ticket, 200);
    }
    
    public function updatePriority(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $priority = (int)$request->priority;
        $ticket->priority = $priority;
        $ticket->save();
        
        $tickets = Ticket::where('status', $ticket->status)
                ->where('priority', '>=', $priority)
                ->get();
        
        foreach ($tickets as $newTicket) {
            $priority ++;
            $newTicket->priority = $priority;
            $newTicket->save();
        }
        
        return response()->json($ticket, 200);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $priority = (int)$request->priority;
        
        $ticket->priority = $priority;
        $ticket->save();
        
        $tickets = Ticket::where('status', $ticket->status)
                ->where('priority', '>=', $priority)
                ->get();
        
        foreach ($tickets as $newTicket) {
            $priority ++;
            $newTicket->priority = $priority;
            $newTicket->save();
        }
        
        return response()->json($ticket, 200);
    }
    
    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        
        return response()->json(null, 204);
    }
}
