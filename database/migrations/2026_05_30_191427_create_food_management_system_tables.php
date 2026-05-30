<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Branches
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->string('employee_code')->unique();
            $table->string('fullname');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('salary', 12, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('fullname');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->integer('loyalty_points')->default(0);
            $table->timestamps();
        });

        // Dining Tables
        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('table_no');
            $table->integer('capacity');
            $table->enum('status', ['Available', 'Reserved', 'Occupied', 'Cleaning']);
            $table->timestamps();
        });

        // Reservations
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->constrained('dining_tables')->cascadeOnDelete();
            $table->dateTime('reservation_date');
            $table->integer('guest_count');
            $table->string('status');
            $table->timestamps();
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Menu Items
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('selling_price', 10, 2);
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Ingredients
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('ingredient_code')->unique();
            $table->string('name');
            $table->string('unit');
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->timestamps();
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code')->unique();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // Purchases
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->date('purchase_date');
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });

        // Purchase Details
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('qty', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Recipes
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity_required', 10, 2);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->foreignId('waiter_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->dateTime('order_date');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['Pending', 'Cooking', 'Served', 'Completed', 'Cancelled']);
            $table->timestamps();
        });

        // Order Details
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Ingredient Preparations
        Schema::create('ingredient_preparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->dateTime('preparation_date');
            $table->decimal('qty', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Kitchen Orders
        Schema::create('kitchen_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chef_id')->constrained('employees')->cascadeOnDelete();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('finish_time')->nullable();
            $table->enum('status', ['Pending', 'Cooking', 'Ready']);
            $table->timestamps();
        });

        // Quality Checks
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchen_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checked_by')->constrained('employees')->cascadeOnDelete();
            $table->dateTime('check_time');
            $table->enum('status', ['Pass', 'Fail']);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Food Servings
        Schema::create('food_servings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waiter_id')->constrained('employees')->cascadeOnDelete();
            $table->dateTime('serving_time');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashier_id')->constrained('employees')->cascadeOnDelete();
            $table->string('invoice_no')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->string('payment_status');
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->dateTime('payment_date');
            $table->timestamps();
        });

        // Dish Washings
        Schema::create('dish_washings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->dateTime('washing_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Kitchen Sanitizings
        Schema::create('kitchen_sanitizings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->dateTime('sanitizing_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'kitchen_sanitizings',
            'dish_washings',
            'payments',
            'invoices',
            'food_servings',
            'quality_checks',
            'kitchen_orders',
            'ingredient_preparations',
            'order_details',
            'orders',
            'recipes',
            'purchase_details',
            'purchases',
            'suppliers',
            'ingredients',
            'menu_items',
            'categories',
            'reservations',
            'dining_tables',
            'customers',
            'employees',
            'roles',
            'branches'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }
};
