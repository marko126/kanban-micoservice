<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
    
    public function show($id)
    {
        return User::find($id);
    }
    
    public function store(Request $request)
    {
        $user = User::create($request->all());
        
        return response()->json($user, 201);
    }
    
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        
        return response()->json($user, 200);
    }
    
    public function delete(User $user)
    {
        $user->delete();
        
        return response()->json(null, 204);
    }
}
