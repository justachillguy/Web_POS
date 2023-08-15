<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Builder;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("name", "LIKE", "%" . $keyword . "%");
            });
        })
        ->latest("id")
        ->paginate(4)
        ->withQueryString();

        return $brands;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create([
            "name" => $request->name,
            "company" => $request->company,
            "information" => $request->information,
            "user_id" => auth()->id(),
        ]);

        return response()->json([
            "brand" => $brand
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        if (is_null($brand)) {
            return response()->json([
                "message" => "product not found"
            ], 404);
        }

        return ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand, User $user)
    {
        if (is_null($brand)) {
            return response()->json([
                "message" => "product not found"
            ], 404);
        }

        if($request->has('name')){
            $brand->name = $request->name;
        }

        if($request->has('company')){
            $brand->company = $request->company;
        }

        if($request->has('information')){
            $brand->information = $request->information;
        }

        $brand->update();
        return response()->json([
            "message" => $brand,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if (is_null($brand)) {
            return response()->json([
                "message" => "product not found"
            ], 204);
        }

        $brand->delete();

        return response()->json([], 204);
    }


}
