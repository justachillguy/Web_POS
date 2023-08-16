<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function chgPassword(Request $request)
    {
        $request->validate([
            "current_password"=>"required|min:8",
            "new_password"=>"required|confirmed",
        ]);

        $userPassword = Auth::user()->password;
        //custom current_password with auth user password
        if(!Hash::check($request->current_password,$userPassword)){
            return redirect()->back()->withErrors(['current_password'=>'Password does not match']);
        }

        //update new password

        $id = auth()->id();
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->new_password);
        $user->update();
        // return $user;
        Auth::user()->currentAccessToken()->delete();
        // return redirect()->route("auth.logout");
        return response()->json([
            "message" => "password change successful",
        ]);
    }

    // public function update(Request $request, User $user)
    // {
    //     if($request->has('photo')){
    //         $user->photo = $request->photo;
    //     }
    //     $user->update();

    //     return response()->json(['message'=>"user's profile photo is updated"]);
    // }
}
