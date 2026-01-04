<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmployeePayHistory;
use App\Models\EmployeesDailyTotal;

class PayrollTestSeeder extends Seeder
{
    public function run()
    {
        // 1. Create an Employee
        $employee = Employee::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'status' => 'active',
        ]);

        // 2. Add Pay History (50% Commission)
        EmployeePayHistory::create([
            'employee_id' => $employee->id,
            'effective_date' => '2023-01-01', // Effective before our test date
            'pay_frequency' => 'bi-weekly',
            'pay_type' => 'commission',
            'commission_card_percent' => 50,
            'commission_cash_percent' => 50,
            'tip_on_card_fee_percent' => 3.0,
            'hourly_rate' => 0,
        ]);

        // 3. Add Daily Totals for Jan 2024
        // Day 1: $100 sales, $20 tips
        EmployeesDailyTotal::create([
            'date' => '2024-01-05',
            'employee_name' => 'John Doe', // Must match First + Last name
            'service_total' => 100.00,
            'tips' => 20.00,
            'hours_worked' => 8,
        ]);
    }
}