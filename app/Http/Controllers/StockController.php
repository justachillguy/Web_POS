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
        /* When searching stock records, the only keyword we're gonna use is the name of the product. So, we have to find the products by the product's name we pass thru parameter. */
        $products = Product::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("name", "LIKE", "%" . $keyword . "%");
            });
        })->get();

        /* If the products we get by the keyword value is empty, we're gonna return them
        empty state.
        */
        if (empty($products)) {
            return response()->json(["message" => "There is no result."]);
        }

        $stocks = Stock::when(request()->has("keyword"), function ($query) use ($products) {
            $query->whereIn("product_id", $products->pluck("id"));
        })
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->latest("id")
            ->paginate(5)
            ->withQueryString();

        if ($stocks->isEmpty()) {
            return response()->json([
                "message" => "There is no stock records yet."
            ]);
        }
        $data = StockResource::collection($stocks);
        return $data->resource;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        Gate::authorize("create", App\Models\Stock::class);

            $stock = new Stock;
            $stock->user_id = auth()->id();
            $stock->product_id = $request->product_id;
            $stock->quantity = $request->quantity;
            $stock->save();

            $product = Product::findOrFail($request->product_id);
            $product->total_stock += $request->quantity;
            $product->update();

            return response()->json(
                [
                    "message" => $request->quantity . " " . $product->name . " are added to the inventory."
                ]
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        Gate::authorize("view", $stock);

        return new StockDetailResource($stock);
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
                "message" => $stock
            ]
        );
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
