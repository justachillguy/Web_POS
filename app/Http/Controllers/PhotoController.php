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
        $photos = Photo::latest("id")->paginate(5)
        ->withQueryString();

        // Checking if there are stored files or not.
        // If not, this message will be returned.
        if ($photos->isEmpty()) {
            return response()->json([
                "message" => "There is no file yet."
            ],404);
        }

        // If there is, resource will be returned.
        $data = PhotoResource::collection($photos);
        return response()->json([
            "photos"=> $data->resource
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {

        /*
        Checking if files are passed along with request
        */
        if ($request->hasFile('photos')) {
            // return $request->file("photos");
            $photos = $request->file('photos');
            // return $photos;

            $savedPhotos = [];
            foreach ($photos as $photo) {
                /*
                Get size of file and convert into kilobytes.
                */
                $bytes = $photo->getSize();
                $kilobytes = $bytes / 1024;
                $kilobytesRounded = round($kilobytes, 2);
                $size = $kilobytesRounded . " KB";

                /*
                store the file in the storage first
                */
                $savedPhoto = $photo->store("public/media");
                // return $savedPhoto;

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
                    "size" => $size,
                    "created_at" => now(),
                    "updated_at" => now()
                ];
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
        ],201);
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
        Get the file's path to the storage.
        */
        $path = $photo->url;

        /*
        Checking if the given file's path exists in the storage.
        */
        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        /*
        Also delete the file in the storage as well as the one in the database.
        */

        $photo->delete();
        return response()->json([
            "message" => "A photo has been deleted",
        ],204);
    }

    /*
    Multiple delete recieve an array of ids of models that are about to be deleted.
    In the case of  multi-delete, we set post method to the verb of the req, not Delete method.
    */
    public function multipleDelete(Request $request)
    {
        /*
        Checking if passed id is an array
        */
        if (!is_array($request->id)) {
            return response()->json([
                "message" => "sth wrong"
            ]);
        }
        $idsToDelete = $request->id;
        $photos = Photo::where("user_id", auth()->id())->whereIn("id", $idsToDelete)->get();
        // return $photos;

        $filePaths = [];
        /*
        Collecting the paths of the files about to be deleted.
        */
        foreach ($photos as $photo) {
            // if (Storage::exists($photo->url)) {
            // }
            $filePaths[] = $photo->url;

        }

        /*
        Filtering only the existing file's paths.
        */
        $filePathsToDelete = array_filter($filePaths, function ($f) {
            return Storage::exists($f);
        });

        // return $filePathsToDelete;

        /*
        I don't want Storage::delete() to operate if any of file's paths doesn't exist.
        */
        if (!empty($filePathsToDelete)) {
            Storage::delete($filePathsToDelete);
        }
        Photo::destroy(collect($idsToDelete));

        return response()->json([
            "message" => "Multiple photos delete successful"
        ],204);
    }
}
