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
>>>>>>> dev

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        Gate::authorize("viewAny", App\Models\Stock::class);

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
