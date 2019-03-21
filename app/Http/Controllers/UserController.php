<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

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
        $validation = Validator::make($request->all(), [
            'name'      => 'required|min:2|max:50',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|max:20'
        ]);
        
        if ($validation->fails()) {
            return $validation->errors()->toJson();
        }
        
        $data = $validation->getData();
        
        $user = User::create($data);
        
        return response()->json($user, 201);
    }
    
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required|min:2|max:50'
        ]);
        
        if ($validation->fails()) {
            return $validation->errors()->toJson();
        }
        
        $data = $validation->getData();
        
        $user = User::findOrFail($id);
        $user->update($data);
        
        return response()->json($user, 200);
    }
    
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json(null, 204);
    }
}
