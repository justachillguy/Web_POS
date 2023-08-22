<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandDetailResource;
use App\Http\Resources\BrandResource;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

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

        if ($brands->isEmpty()) {
            return response()->json([
                "message" => "There is no brand records yet."
            ]);
        }

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {

        Gate::authorize("create", App\Models\Brand::class);

        if ($request->has("information")) {
            $brand = Brand::create([
                "name" => $request->name,
                "company" => $request->company,
                "agent" => $request->agent,
                "phone_number" => $request->phone_number,
                "information" => $request->information,
                "user_id" => auth()->id(),
            ]);
        } else {
            $brand = Brand::create([
                "name" => $request->name,
                "company" => $request->company,
                "agent" => $request->agent,
                "phone_number" => $request->phone_number,
                "user_id" => auth()->id(),
            ]);
        }

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

        return new BrandDetailResource($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        Gate::authorize("update", $brand);

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

        if($request->has('agent')){
            $brand->agent = $request->agent;
        }

        if($request->has('phone_number')){
            $brand->phone_number = $request->phone_number;
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
        Gate::authorize("delete", $brand);

        $brand->delete();

        return response()->json([
            "message" => "A brand has been deleted."
        ]);
    }


}
