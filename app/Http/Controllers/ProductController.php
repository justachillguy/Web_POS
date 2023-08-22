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

        if ($products->isEmpty()) {
            return response()->json([
                "message" => "There is no product records yet."
            ]);
        }

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize("create", App\Models\Product::class);


            if ($request->has("information")) {
                $product = Product::create(
                    [
                    "name" => $request->name,
                    "brand_id" => $request->brand_id,
                    "actual_price" =>$request->actual_price,
                    "sale_price" =>$request->sale_price,
                    "unit" =>$request->unit,
                    "more_information" =>$request->more_information,
                    "user_id" => auth()->id(),
                    ]
                    );
            } else {
                $product = Product::create(
                    [
                    "name" => $request->name,
                    "brand_id" => $request->brand_id,
                    "actual_price" =>$request->actual_price,
                    "sale_price" =>$request->sale_price,
                    "unit" =>$request->unit,
                    "user_id" => auth()->id(),
                    ]
                    );
            }

        //     if($request->has("total_stock")){

        //     }

        // Stock::create(
        //     [
        //         "user_id" => auth()->id(),
        //         "product_id" => $product->id,
        //         "quantity" => $product->total_stock,
        //         "more" => $product->more_information,
        //     ]
        //     );

        return new ProductDetailResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductDetailResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,Product $product)
    {
        $this->authorize("update", $product);
        // $product = Product::findOrFail($id);

        // if (!is_null($product)) {
        //     return response()->json([
        //         "message" => "product not found"
        //     ]);
        // }
        // $oldTotalStock = $product->total_stock;


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

        // $quantity = $newTotalStock - $oldTotalStock;

        // Stock::create(
        //     [
        //         "user_id" => auth()->id(),
        //         "product_id" => $product->id,
        //         "quantity" => $quantity,
        //         "more" => $product->more_information,
        //     ]
        //     );

        return response()->json(["message" => "Success"]);

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
            ]
        );

    }
}
