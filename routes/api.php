<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController; // <--- Add this line
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ShopDailyTotalController;
use App\Http\Controllers\EmployeesDailyTotalController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('shop-daily-totals', ShopDailyTotalController::class);
    Route::apiResource('employees-daily-totals', EmployeesDailyTotalController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('expense-categories', ExpenseCategoryController::class);
    Route::get('/payroll/calculate', [PayrollController::class, 'calculate']);
});
