<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('guest:sanctum')->only('register', 'login');
        $this->middleware('auth:sanctum')->only('logout', 'check_token');
    }
    //
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|min:5',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('userTokenName')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        
        return Response($response, 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if(Auth::attempt($request->only('email', 'password'))){
            $user = User::find(auth()->user()->id);
            return Response([
                'user' => $user,
                'token' => $user->createToken('userTokenName')->plainTextToken,
            ]);
        }
        return Response([
            'error' => 'Your informations are incorrect !'
        ]);
    }
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        auth()->user()->tokens()->delete();
        return "Your logged out";
    }
    public function check_token(Request $request){
        return response([
            'message' => 'Authenticated'
        ]);
    }
}
