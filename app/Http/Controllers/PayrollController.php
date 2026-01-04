<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeesDailyTotal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:employees,id'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Fetch employees with their pay history effective before or during the period
        $query = Employee::with(['payHistory' => function ($q) use ($endDate) {
            $q->where('effective_date', '<=', $endDate)
              ->orderBy('effective_date', 'desc');
        }]);

        if ($request->employee_id) {
            $query->where('id', $request->employee_id);
        }

        $employees = $query->get();
        $payrollData = [];

        foreach ($employees as $employee) {
            // Get the most recent pay settings
            $paySettings = $employee->payHistory->first();
            if (!$paySettings) continue;

            // Match daily totals by name (assuming "First Last" format matches your data)
            $fullName = $employee->first_name . ' ' . $employee->last_name;
            
            $totals = EmployeesDailyTotal::where('employee_name', $fullName)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $sumHours = $totals->sum('hours_worked');
            $sumService = $totals->sum('service_total');
            $sumTips = $totals->sum('tips');

            // Calculate Wage
            $grossWage = 0;
            if ($paySettings->pay_type === 'hourly') {
                $grossWage = $sumHours * $paySettings->hourly_rate;
            } else {
                // Commission Calculation (Simplified: applies card % to total service)
                $grossWage = $sumService * ($paySettings->commission_card_percent / 100);
            }

            // Calculate Tips & Fees
            $tipFee = $sumTips * ($paySettings->tip_on_card_fee_percent / 100);
            $netTips = $sumTips - $tipFee;

            $totalPay = $grossWage + $netTips;

            $payrollData[] = [
                'employee_id' => $employee->id,
                'name' => $fullName,
                'total_hours' => $sumHours,
                'total_sales' => $sumService,
                'gross_wage' => round($grossWage, 2),
                'net_tips' => round($netTips, 2),
                'total_pay' => round($totalPay, 2),
            ];
        }

        return response()->json($payrollData);
    }
}