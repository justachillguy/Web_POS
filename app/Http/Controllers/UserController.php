<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list()
    {
        Gate::authorize("admin-only", App\Models\User::class);
        $users = User::latest("id")
        ->paginate(4)
        ->withQueryString();

        return response()->json([
            "users" => $users,
        ]);
    }


    public function create(Request $request){
        Gate::authorize("admin-only", App\Models\User::class);

        $request->validate([
            "name" => ["required", "min:3"],
            "phone_number" => ["string", "required"],
            "date_of_birth" => ["required", "date"],
            "gender" => ["required", "in:male,female"],
            "position" => ["required", "in:admin,staff"],
            "address" => ["required", "max:255"],
            "email" => ["required", "email", "unique:users"],
            "password" => ["required", "min:8", "confirmed"]
        ]);

        // return response()->json([
        //     "message" => $request,
        // ]);

        User::create([
            "name" => $request->name,
            "phone_number" => $request->phone_number,
            "date_of_birth" => $request->date_of_birth,
            "gender" => $request->gender,
            "address" => $request->address,
            "position" => $request->position,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "message" => "User register successful",
        ]);

    }

    public function updateRole(Request $request, $id)
    {
        Gate::authorize("admin-only", App\Models\User::class);
        $user = User::findOrFail($id);

        $request->validate([
            "position" => ["required", "in:admin,staff"],
        ]);


        if ($request->has("position")) {
            // return $request;
            $user->position = $request->position;
        }

        $user->update();

        return response()->json(
            [
                "message" => "A staff has been promoted",
                "user" => $user,
            ]
        );
    }
}
