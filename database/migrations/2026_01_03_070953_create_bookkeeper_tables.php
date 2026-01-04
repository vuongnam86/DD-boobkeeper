<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('nickname', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('ssn', 20)->nullable();
            $table->date('birthday')->nullable();
            $table->string('street_address', 255)->nullable();
            $table->string('unit_number', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->boolean('mailing_is_same')->default(true);
            $table->string('mailing_street_address', 255)->nullable();
            $table->string('mailing_unit_number', 50)->nullable();
            $table->string('mailing_city', 100)->nullable();
            $table->string('mailing_state', 50)->nullable();
            $table->string('mailing_zip_code', 20)->nullable();
            $table->string('driver_license_number', 50)->nullable();
            $table->string('driver_license_state', 50)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Employee Pay History
        Schema::create('employee_pay_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('effective_date');
            $table->enum('pay_frequency', ['weekly', 'bi-weekly', 'monthly'])->default('bi-weekly');
            $table->enum('pay_type', ['commission', 'hourly'])->default('commission');
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('tip_on_card_fee_percent', 5, 2)->default(0);
            $table->decimal('commission_card_percent', 5, 2)->default(0);
            $table->decimal('commission_cash_percent', 5, 2)->default(0);
            $table->decimal('guaranteed_minimum_amount', 10, 2)->default(0);
            $table->enum('payout_split_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('check_split_percentage', 5, 2)->default(50);
            $table->decimal('check_split_fixed_amount', 10, 2)->default(0);
            $table->timestamps();
        });

        // 3. Employee Licenses
        Schema::create('employee_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('license_type', 100);
            $table->string('license_number', 100)->nullable();
            $table->date('expiration_date')->nullable();
        });

        // 4. Shop Daily Total
        Schema::create('shop_daily_total', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('cash', 10, 2)->default(0);
            $table->decimal('venmo', 10, 2)->default(0);
            $table->decimal('zelle_boa', 10, 2)->default(0);
            $table->decimal('zelle_chase', 10, 2)->default(0);
            $table->decimal('card', 10, 2)->default(0);
            $table->decimal('gift_card_use', 10, 2)->default(0);
            $table->decimal('gift_card_sale', 10, 2)->default(0);
            $table->decimal('sale_product', 10, 2)->default(0);
            $table->decimal('reward', 10, 2)->default(0);
            $table->decimal('promo', 10, 2)->default(0);
            $table->decimal('withdraw', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Shop Daily Total Raw
        Schema::create('shop_daily_total_raw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_daily_total_id')->constrained('shop_daily_total')->onDelete('cascade');
            $table->string('field_name', 50);
            $table->text('raw_value')->nullable();
        });

        // 6. Employees Daily Total
        Schema::create('employees_daily_total', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('employee_name', 255);
            $table->decimal('service_total', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tips', 10, 2)->default(0);
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->text('service_total_raw')->nullable();
            $table->text('discount_raw')->nullable();
            $table->text('tips_raw')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Expense Categories
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->integer('sort_order')->default(0);
        });

        // Seed default categories
        DB::table('expense_categories')->insert([
            ['name' => 'Supplies', 'sort_order' => 1],
            ['name' => 'Rent', 'sort_order' => 2],
            ['name' => 'Utilities', 'sort_order' => 3],
            ['name' => 'Maintenance', 'sort_order' => 4],
            ['name' => 'Other', 'sort_order' => 5],
        ]);

        // 8. Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('description', 255);
            $table->decimal('amount', 10, 2);
            $table->string('category', 255)->nullable();
            $table->string('receipt_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('employees_daily_total');
        Schema::dropIfExists('shop_daily_total_raw');
        Schema::dropIfExists('shop_daily_total');
        Schema::dropIfExists('employee_licenses');
        Schema::dropIfExists('employee_pay_history');
        Schema::dropIfExists('employees');
    }
};
