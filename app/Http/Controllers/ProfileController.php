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
            "current_password" => "required|min:8",
            "new_password" => "required|confirmed",
        ]);

        $userPassword = Auth::user()->password;
        //custom current_password with auth user password
        if (!Hash::check($request->current_password, $userPassword)) {
            return redirect()->back()->withErrors(['current_password' => 'Password does not match']);
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


    public function update(Request $request, $id)
    {

        $request->validate([
            "name" => ["nullable", "string", "min:3"],
            "phone_number" => ["nullable", "string"],
            "date_of_birth" => ["nullable", "date"],
            "gender" => ["nullable", "in:male,female"],
            "address" => ["nullable", "string", "max:300"],
            "photo" => ['nullable', 'string',]

        ]);

        $user = User::findOrFail($id);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->has('date_of_birth')) {
            $user->date_of_birth = $request->date_of_birth;
        }

        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }

        if ($request->has('address')) {
            $user->address = $request->address;
        }

        if ($request->has('photo')) {
            $user->photo = $request->photo;
        }

        $user->update();

        return response()->json(['message' => "user's info is updated"]);
    }
}
