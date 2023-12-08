<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::when(request()->has("keyword"), function ($query) {
            $query->where(function ($query) {
                $keyword = request()->keyword;
                $query->where("name", "LIKE", "%" . $keyword . "%")->orWhere(function ($query) {
                    $query->whereHas("brand", function ($query) {
                        $query->where("name", "LIKE",  "%" . request()->keyword . "%");
                    });
                });
            });
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();

        // $product = Product::when(request()->has('keyword'),function($query){
        //     $keyword = request()->keyword;
        //     $query->where('name','like','%'.$keyword,'%');
        // })->latest('id')->paginate(4)->withQueryString();

        if ($products->isEmpty()) {
            return response()->json([
                "message" => "There is no product records yet."
            ]);
        }
        $data = ProductResource::collection($products);
        return response()->json([
            "products" => $data->resource
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize("create", App\Models\Product::class);

        $product = new Product;
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        // $product->total_stock = $request->total_stock;
        $product->actual_price = $request->actual_price;
        $product->sale_price = $request->sale_price;
        $product->total_stock = $request->total_stock;
        $product->unit = $request->unit;
        $product->more_information = $request->more_information;
        $product->photo = $request->photo;
        $product->user_id = auth()->id();
        $product->save();

        return response()->json([
            "message" => "New product $product->name is created",
            "product" => new ProductDetailResource($product)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            "product" => new ProductDetailResource($product)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize("update", $product);

        if ($request->has('name')) {
            $product->name = $request->name;
        }

        if ($request->has('brand_id')) {
            $product->brand_id = $request->brand_id;
        }

        if ($request->has('actual_price')) {
            $product->actual_price = $request->actual_price;
        }

        if ($request->has('sale_price')) {
            $product->sale_price = $request->sale_price;
        }

        if ($request->has('unit')) {
            $product->unit = $request->unit;
        }

        if ($request->has('more_information')) {
            $product->more_information = $request->more_information;
        }

        if ($request->has('user_id')) {
            $product->user_id = $request->user_id;
        }

        if ($request->has('photo')) {
            $product->photo = $request->photo;
        }

        $product->update();

        return response()->json(
            [
                "message" => "Product $product->name is updated.",
                "updatedProduct" => $product
            ],
            200
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize("delete", $product);
        $product->delete();

        return response()->json(
            [
                "message" => "A product has been deleted."
            ],
            204
        );
    }
}
