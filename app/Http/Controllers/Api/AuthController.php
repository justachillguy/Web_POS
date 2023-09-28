<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:6"
        ]);

        /*  Checking if given credentials are valid.
            If not, tokens will not be returned.
        */
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                "message" => "Invalid login details",
            ]);
        }

        /*  Checking if a user is banned or not after loggin in process.
            If not, tokens will not be returned.
        */
        if ($request->user()->ban_status === "true") {
            return response()->json([
                "message" => "You have been banned. Could not login at the moment."
            ]);
        }


        // return Auth::user()->createToken($request->has("device") ? $request->device : "unknown");
        return response()->json([
            "plainTextToken" => Auth::user()->createToken($request->has("device") ? $request->device : "unknown")->plainTextToken
        ]);

    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successful",
        ]);
    }

    public function logoutAll(){
        foreach (Auth::user()->tokens as $token) {
            $token->delete();
        }

        return response()->json([
            "message" => "logout all devices",
        ]);
    }

    public function devices()
    {
        return Auth::user()->tokens;
    }

}
