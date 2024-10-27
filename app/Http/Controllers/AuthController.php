<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
class AuthController extends Controller
{
    /**
     * generate random 6-digits code
     */
    private function generateCode() {
        $code = rand(111111,999999);
        return $code;
    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'phone_number' => 'required|integer|regex:/^\d{10,13}$/', 
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $token = Str::random(60);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'code' => $this->generateCode(),
            'password' => Hash::make($request->password),
            'remember_token' => $token
        ]);

        return response()->json([
            'name' => $user->name,
            'phone_number' => $user->phone_number,
            'token' => $user->remember_token
        ], 200);
    }

    public function verifyByCode(Request $request) {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $user = User::where('remember_token', $token)->first();

        $request->validate([
            'code' => 'required|integer|max:999999|min:111111',
        ]);

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        if( gettype($user->code) !== "integer") return response()->json(['error' => 'user code'], 401);
        if( gettype(intval($request->code)) !== "integer") return response()->json(['error' => 'request code'.gettype($request->code)], 401);

        if ($user->code === intval($request->code)) {
            $user->is_verified = true;
            $user->save();

            return response()->json([
                'message' => 'User verified successfully'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Invalid or expired code'
            ], 400);
        }
    }
    public function login(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['phone number or password incorrect'],
            ]);
        }

        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'name' => $user->name,
            'token' => $user->remember_token,
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
