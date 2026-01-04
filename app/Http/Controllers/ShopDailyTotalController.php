<?php

namespace App\Http\Controllers;

use App\Models\ShopDailyTotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopDailyTotalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShopDailyTotal::with('rawValues')->orderBy('date', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'cash' => 'numeric',
            'card' => 'numeric',
        ]);

        return DB::transaction(function () use ($request) {
            // Create the main total record
            $dailyTotal = ShopDailyTotal::create($request->except('raw_values'));

            // Create associated raw values if provided
            if ($request->has('raw_values')) {
                $dailyTotal->rawValues()->createMany($request->input('raw_values'));
            }

            return $dailyTotal->load('rawValues');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopDailyTotal $shopDailyTotal)
    {
        return $shopDailyTotal->load('rawValues');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopDailyTotal $shopDailyTotal)
    {
        $request->validate([
            'date' => 'sometimes|date',
            'cash' => 'numeric',
            'card' => 'numeric',
        ]);

        $shopDailyTotal->update($request->except('raw_values'));
        return $shopDailyTotal->load('rawValues');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopDailyTotal $shopDailyTotal)
    {
        $shopDailyTotal->delete();
        return response()->noContent();
    }
}