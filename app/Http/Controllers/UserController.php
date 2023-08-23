<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Hamcrest\Type\IsBoolean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list()
    {
        // Gate::authorize("admin-only", App\Models\User::class);
        $users = User::latest("id")
        ->paginate(4)
        ->withQueryString();

        if ($users->isEmpty()) {
            return response()->json([
                "message" => "There is no user records yet."
            ]);
        }

        // return response()->json([
        //     "users" => $users,
        // ]);
        return UserResource::collection($users);
    }

    public function details($id)
    {
        $user = User::findOrFail($id);
        return new UserDetailResource($user);
    }

    public function create(Request $request){
        // Gate::authorize("admin-only", App\Models\User::class);

        $request->validate([
            "name" => ["required", "min:3"],
            "phone_number" => ["string", "required"],
            "date_of_birth" => ["required", "date"],
            "gender" => ["required", "in:male,female"],
            "position" => ["required", "in:admin,staff"],
            "address" => ["required", "max:255"],
            "email" => ["required", "email", "unique:users"],
            "password" => ["required", "min:8", "confirmed"],
            "photo" => ["required", "string"],
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
            "password" => Hash::make($request->password),
            "photo" => $request->photo,
        ]);

        return response()->json([
            "message" => "User register successful",
        ]);

    }

    public function updatePosition(Request $request, $id)
    {
        // Gate::authorize("admin-only", App\Models\User::class);
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

    public function ban(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->ban_status = "true";
        $user->update();
        return response()->json(
            [
                "message" => "You have been banned for being a really bad boy"
            ]
        );

    }

    public function unban(Request $request)
    {
        $user = User::findOrFail($request->id);

        if ($user->ban_status === "false") {
            return response()->json([
                "message" => "This user is not banned."
            ]);
        }
        $user->ban_status = "false";
        $user->update();
        return response()->json(
            [
                "message" => "You have been unbanned.Be a good boy now."
            ]
        );

    }
}
