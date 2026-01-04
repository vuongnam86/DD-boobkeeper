<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ExpenseCategory::orderBy('sort_order')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:expense_categories,name|max:255',
            'sort_order' => 'integer',
        ]);

        return ExpenseCategory::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        return $expenseCategory;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'sometimes|string|unique:expense_categories,name,' . $expenseCategory->id . '|max:255',
            'sort_order' => 'integer',
        ]);

        $expenseCategory->update($request->all());
        return $expenseCategory;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return response()->noContent();
    }
}