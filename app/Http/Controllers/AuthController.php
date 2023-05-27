<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'gender' => 'required|string',
            'Username' => 'required|string',
            'phone'=> 'required|string',
        ]);

        $user = User::create ([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'Username'=> $fields['Username'] ?? '',
            'gender'=> $fields['gender'],
            'phone' => $fields['phone'] ?? '',
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user ->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //check email
        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
                return response([
                        'message' => 'Bad creds'
                ], 401);
        };
        $token = $user ->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => response()->json($user),
            'token' => $token,
            'role' =>$user->role,
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth() -> user() -> tokens() -> delete();
        return [
            'message' => 'Logged out'
        ];

    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,' . $user->id,
            'phone' => 'required|string',
        ]);

        $user->name = $fields['name'];
        $user->email = $fields['email'];
        $user->phone = $fields['phone'];

        $user->save();

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
}
