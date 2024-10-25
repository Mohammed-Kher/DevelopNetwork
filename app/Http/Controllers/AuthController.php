<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    public function register(Request $request)
{
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            ]);
        dd($validatedData);
            $user = User::create($validatedData);
            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }


    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['Username or password incorrect'],
            ]);
        }

        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'name' => $user->name,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }


    public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();
                    return response()->json(
                        [
                            'status' => 'success',
                            'message' => 'User logged out successfully'
                        ]);
    }

}
