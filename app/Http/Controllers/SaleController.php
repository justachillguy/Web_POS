<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class SaleController extends Controller
{
    public function checkout(Request $request)
    {

        try {
            DB::beginTransaction();

            // return $request;
            //collect([array])
            $productIds = collect($request->items)->pluck("product_id");
            // return $productIds; //[1,2,3]
            $products = Product::whereIn("id", $productIds)->get();
            // dd($products);

            $total = 0;

            foreach ($request->items as $item) {
                $total += $item["quantity"] * $products->find($item["product_id"])->sale_price;
            }

            $tax = $total * 0.05;
            $netTotal = $total + $tax;

            $voucher = Voucher::create([
                "voucher_number" => fake()->uuid(),
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
                "user_id" => auth()->id(),
            ]);

            $records = [];

            foreach ($request->items as $item) {

                $currentProduct = $products->find($item["product_id"]);
                $records[] = [
                    "voucher_id" => $voucher->id,
                    "product_id" => $item["product_id"],

                    "quantity" => $item["quantity"],
                    "cost" => $item["quantity"] * $currentProduct->sale_price,
                    "created_at" => now(),
                    "updated_at" => now()
                ];
                Product::where("id", $item["product_id"])->update([
                    "total_stock" => $currentProduct->total_stock - $item["quantity"]
                ]);
            }

            VoucherRecord::insert($records); // use database

            DB::commit();

            return ;

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return response()->json(
                [
                    "message" => "Caught Exception: " . $th->getMessage() . "on " . $th->getLine(),
                ],
                500
            );
        }
    }

    public function list()
    {
        $vouchers = Voucher::when(request()->has("keyword"), function ($query) {

            $keyword = request()->keyword;

            $query->where("voucher_number", "LIKE", "%" . $keyword . "%");
            $query->orWhere("created_at", "LIKE", "%" . $keyword . "%");
        })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();

        return VoucherResource::collection($vouchers);
    }
}
