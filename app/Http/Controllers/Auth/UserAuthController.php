<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Passport\Client;

class UserAuthController extends Controller
{
    // Method to register a new user
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response([ 'user' => $user, 'token' => $token]);
    }

    // Method to login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($data)) {
            return response(['error_message' => 'Incorrect Details. Please try again']);
        }

        $user = auth()->user();
        $token = $user->createToken('API Token')->accessToken;

        return response(['user' => Auth::user(), 'token' => $token]);
    }
}
