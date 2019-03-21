<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $validation = Validator::make($request->all(), [
            'title'         => 'required|min:5|max:255',
            'description'   => 'required',
            'status'        => 'required|in:1,2,3',
            'user_id'       => 'required|exists:users,id'
        ]);
        
        if ($validation->fails()) {
            return $validation->errors()->toJson();
        }
        
        $data = $validation->getData();
        
        $lowPriority = Ticket::where('status', $data['status'])
                ->orderBy('priority', 'desc')
                ->first();
        
        $data['priority'] = (int)$lowPriority['priority'] + 1;
        
        $ticket = Ticket::create($data);
        
        return response()->json($ticket, 201);
    }
    
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $validation = Validator::make($request->all(), [
            'title'         => 'required|min:5|max:255',
            'description'   => 'required'
        ]);
        
        if ($validation->fails()) {
            return $validation->errors()->toJson();
        }
        
        $data = $validation->getData();
        
        $ticket->update($data);
        
        return response()->json($ticket, 200);
    }
    
    public function updatePriority(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $validation = Validator::make($request->all(), [
            'priority' => 'required|integer|min:1'
        ]);
        
        if ($validation->fails()) {
            return $validation->errors()->toJson();
        }
        
        $data = $validation->getData();
        
        $priority = $data['priority'];
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
        
        $validation = Validator::make($request->all(), [
            'status' => 'required|in:1,2,3'
        ]);
        
        if ($validation->fails()) {
            return $validation->errors()->toJson();
        }
        
        $data = $validation->getData();
        
        $status = $data['status'];
        
        $ticket->status = $status;
        $ticket->save();
        
        return response()->json($ticket, 200);
    }
    
    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        
        return response()->json(null, 204);
    }
}
