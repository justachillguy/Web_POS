<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\StockDetailResource;
use App\Http\Resources\StockResource;
use App\Models\Product;
use Exception;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PhpParser\Node\Stmt\TryCatch;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize("viewAny", App\Models\Stock::class);

<<<<<<< HEAD
                $builder->where("name", "LIKE", "%" . $keyword . "%");
            });
        })->paginate(10)->withQueryString();

        /* If the products we get by the keyword value is empty, we're gonna return them
        empty state.
        */
        if (empty($products)) {
            return response()->json(["message" => "There is no result."],404);
=======
            /*
            When searching stock records, the only keyword we're gonna use is
            the name of the product. So, we have to find the products
            by the product's name we pass thru parameter.
            */
        $ids = [];
        if (request()->has("keyword")) {
            $keyword = request()->keyword;
            $prodIDs = Product::where("name", "LIKE", "%" . $keyword . "%")
            ->Orwhere(function ($query) {
                $query->whereHas("brand", function ($query) {
                    $query->where("name", "LIKE", request()->keyword);
                });
            })
            ->get()
            ->pluck("id")
            ->toArray();

            $ids = $prodIDs;
            if (empty($ids)) {
                return response()->json(["message" => "There is no result."]);
            }
>>>>>>> a2a76d045a9d518544805d5e81cccf40cdce2675
        }

        /*
        If the products we get by the keyword value is empty,
        we're gonna return them the empty state.
        */

        $stocks = Stock::when(request()->has("keyword"), function ($query) use ($ids) {
            $query->whereIn("product_id", $ids);

        })
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();


        // $stocks = Stock::when(request()->has("id"), function ($query) {
        //     $sortType = request()->id ?? "asc";
        //     $query->orderBy("id", $sortType);
        // })
        //     ->latest("id")
        //     ->paginate(10)
        //     ->withQueryString();


        if ($stocks->isEmpty()) {
            return response()->json([
                "message" => "There is no stock records yet."
            ],404);
        }
        $data = StockResource::collection($stocks);
        return response()->json([
            "stocks"=>$data->resource
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request, $prodID)
    {
        Gate::authorize("create", App\Models\Stock::class);

        $product = Product::findOrFail($prodID);

        Stock::create(
            [
                "user_id" => auth()->id(),
                "product_id" => $product->id,
                "quantity" => $request->quantity,
                "more"=>$request->more
            ]
        );

        $product->total_stock += $request->quantity;
        $product->update();

        return response()->json(
            [
                "message" => "$request->quantity quantities are added to product $product->name."
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        Gate::authorize("view", $stock);

        return response()->json([
            "stock"=>new StockDetailResource($stock)
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, Stock $stock)
    {
        Gate::authorize("update", $stock);

        $oldValue = $stock->quantity;

        if ($request->has('product_id')) {
            $stock->product_id = $request->product_id;
        }

        if ($request->has('quantity')) {
            $newValue = $request->quantity;
            $stock->quantity = $request->quantity;
        }

        $stock->update();

        $product = Product::findOrFail($stock->product_id);

        // if ($newValue > $oldValue) {
        //     $addition = $newValue - $oldValue;
        //     $product->total_stock = $product->total_stock + $addition;
        // } else {
        //     $toSubtract = $oldValue - $newValue;
        //     $product->total_stock = $product->total_stock - $toSubtract;
        // }

        $addition = $newValue - $oldValue;
        $toSubtract = $oldValue - $newValue;

        // $newValue > $oldValue ? $product->total_stock = $product->total_stock + $addition : $product->total_stock = $product->total_stock - $toSubtract;
        $product->total_stock = $newValue > $oldValue ? $product->total_stock + $addition : $product->total_stock - $toSubtract;
        $product->update();

        return response()->json(
            [
                "updatedStock"=>$stock
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Stock $stock)
    // {
    //     Gate::authorize("delete", $stock);

    //     $stock->delete();
    //     return response()->json([], 204);
    // }
}
