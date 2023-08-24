<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Resources\ItemsInVoucherResource;
use App\Http\Resources\VoucherResource;
use App\Models\VoucherRecord;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($voucher_number)
    {

        // return $voucher_number;
        $voucher  =  Voucher::where('voucher_number',$voucher_number)->first();

        $voucher_id = $voucher->id;
        // $tax = $voucher->tax;
        // $total = $voucher->total;
        // $netTotal = $voucher->net_total;

        $voucherRecords = VoucherRecord::where("voucher_id", $voucher_id)->get();

        return response()->json(
            [
                "items" => ItemsInVoucherResource::collection($voucherRecords),
                // "total" => $total,
                // "tax" => $tax,
                // "net_total" => $netTotal,
                "voucher" => new VoucherResource($voucher),
            ]
        );

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
