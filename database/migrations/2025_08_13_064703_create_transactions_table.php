<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('transaction_id')->primary()->autoIncrement();
            $table->integer('user_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->integer('status_id')->nullable();
            $table->date('transaction_date')->nullable();
            
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('office_id')->references('office_id')->on('locations');
            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('status_id')->references('status_id')->on('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
