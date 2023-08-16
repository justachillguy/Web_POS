<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::latest("id")
        ->paginate(5)
        ->withQueryString();

        return PhotoResource::collection($photos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        /*
        Checking if files are passed along with request
        */
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $savedPhotos = [];
            foreach ($photos as $photo) {

                /*
                store the file in the storage first
                */
                $savedPhoto = $photo->store("public/media");

                /*
                Using php built-in method, pathinfo()
                which returns information about the file's path
                such as filename, extension and directory of the file path.
                */
                $path_parts = pathinfo($savedPhoto);
                $filename = $path_parts["filename"];
                $extension = $path_parts["extension"];

                $savedPhotos[] = [
                    "name" => $filename,
                    "user_id" => auth()->id(),
                    "url" => $savedPhoto,
                    "ext" => $extension,
                    "created_at" => now(),
                    "updated_at" => now()
                ];
                // $savedPhotos[] = $savedPhoto;
            }
            /*
            Using insert method of Eloquent ORM to create multiple models at one time.
            Better for database performance.
            To void repeated operation of database in one time.
            */
            Photo::insert($savedPhotos);
        }


        return response()->json([
            "message" => "Photos uploaded successful"
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        /*
        Get the file's path in the storage.
        */
        $path = $photo->url;

        /*
        Checking if the given file's path exists in the storage.
        */
        if (!Storage::exists($path)) {
            return response()->json([
                "message" => "File does not exist in storage folder.",
            ]);
        }

        /*
        Also delete the file in the storage as well as the one in the database.
        */
        Storage::delete($path);
        $photo->delete();
        return response()->json([
            "message" => "A photo has been deleted",
        ]);
    }

    /*
    Multiple delete recieve an array of ids of models that are about to be deleted.
    In the case of  multi-delete, we set post method to the verb of the req, not Delete method.
    */
    public function multipleDelete(Request $request)
    {

        if (!is_array($request->id)) {
            return response()->json([
                "message" => "sth wrong"
            ]);
        }
        $idsToDelete = $request->id;
        $photos = Photo::where("user_id", auth()->id())->whereIn("id", $idsToDelete)->get();
        $filePathsToDelete = [];
        foreach ($photos as $photo) {
            $filePathsToDelete[] = $photo->url;
        }
        // if (Storage::exists($filePathsToDelete)) {

        //     return $filePathsToDelete;
        // }
        return response()->json([
            "message" => "successful"
        ]);

    }
}
