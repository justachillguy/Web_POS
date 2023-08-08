<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
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
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("name", "LIKE", "%" . $keyword . "%");
            });
        })
        ->latest("id")
        ->paginate(4)
        ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // $this->authorize("create");
        $product = Product::create(
            [
            "name" => $request->name,
            "brand_id" => $request->brand_id,
            "actual_price" =>$request->actual_price,
            "sale_price" =>$request->sale_price,
            "total_stock" =>$request->total_stock,
            "unit" =>$request->unit,
            "more_information" =>$request->more_information,
            "user_id" => auth()->id(),
            ]
            );

        Stock::create(
            [
                "user_id" => auth()->id(),
                "product_id" => $product->id,
                "quantity" => $product->total_stock,
                "more" => $product->more_information,
            ]
            );

        return response()->json([
            "message" => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (is_null($product)) {
            return response()->json([
                "message" => "product not found"
            ], 404);
        }

        return new ProductDetailResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,Product $product)
    {
        // $this->authorize("update");
        // $product = Product::findOrFail($id);

        // if (!is_null($product)) {
        //     return response()->json([
        //         "message" => "product not found"
        //     ]);
        // }
        $oldTotalStock = $product->total_stock;


        if($request->has('name')){
            $product->name = $request->name;
        }

        if($request->has('brand_id')){
            $product->brand_id = $request->brand_id;
        }

        if($request->has('actual_price')){
            $product->actual_price = $request->actual_price;
        }

        if($request->has('sale_price')){
            $product->sale_price = $request->sale_price;
        }

        if($request->has('total_stock')){
            $newTotalStock = $request->total_stock;
            $product->total_stock = $newTotalStock;
        }

        if($request->has('unit')){
            $product->unit = $request->unit;
        }

        if($request->has('more_information')){
            $product->more_information = $request->more_information;
        }

        if($request->has('user_id')){
            $product->user_id = $request->user_id;
        }

        if($request->has('photo')){
            $product->photo = $request->photo;
        }

        $product->update();

        $quantity = $newTotalStock - $oldTotalStock;

        Stock::create(
            [
                "user_id" => auth()->id(),
                "product_id" => $product->id,
                "quantity" => $quantity,
                "more" => $product->more_information,
            ]
            );

        return response()->json(
            [
                "message" => $product
            ]
        );


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // $this->authorize("delete");
        $product->delete();
        return response()->json(
            [],403
        );
    }
}
