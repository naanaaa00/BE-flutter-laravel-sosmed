<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Menggunakan 'User' model yang benar

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attrs = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $attrs['username'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
        ]);
    
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('authToken')->plainTextToken // Menggunakan nama token yang unik
        ]);
    }

    public function login(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($attrs)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 403); // Menggunakan status kode HTTP 403 untuk akses ditolak
        }

        return response()->json([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken // Menggunakan nama token yang unik
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Succes'
        ], 200);
    }

    public function profile()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

    public function update(Request $request)
    {
        $attrs = $request->validate([
            'username' => 'required|string' 
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        auth()->user()->update([
            'username' => $attrs['username'],
            'image' => $image
        ]);

        return response()->json([
            'message' => 'user updated',
            'user' => auth()->user()
        ],200);
       
    }
}
