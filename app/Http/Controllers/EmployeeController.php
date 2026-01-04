<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all employees with their related data
        return Employee::with(['payHistory', 'licenses'])->orderBy('sort_order')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'status' => 'in:active,inactive',
            'pay_history' => 'array',
            'licenses' => 'array',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Create the main Employee record
            // We use except() to exclude the nested arrays for relations
            $employee = Employee::create($request->except(['pay_history', 'licenses']));

            // 2. Create Pay History records if provided
            if ($request->has('pay_history')) {
                $employee->payHistory()->createMany($request->input('pay_history'));
            }

            // 3. Create License records if provided
            if ($request->has('licenses')) {
                $employee->licenses()->createMany($request->input('licenses'));
            }

            return $employee->load(['payHistory', 'licenses']);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $employee->load(['payHistory', 'licenses']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'nullable|email|max:255',
            'status' => 'in:active,inactive',
            'pay_history' => 'array',
            'licenses' => 'array',
        ]);

        $employee->update($request->except(['pay_history', 'licenses']));
        return $employee->load(['payHistory', 'licenses']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->noContent();
    }
}