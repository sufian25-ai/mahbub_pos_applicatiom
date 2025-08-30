<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
    	$data = $request->validate([
    			'email' => 'required|email',
    			'password' => 'required|'
    	]);

    	$user = User::where('email', $data['email'])->first();
    	if(!$user || !Hash::check($data['password'], $user->password))
    	{
    		return response()->json(['message' => 'Invalid credentials'], 401);
    	}

    	$token = $user->createToken('pos-token')->plainTextToken;

    	return response()->json([

    		'token' => $token,
    		'user' => $user,
    	]);
    }   
}
