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
        Schema::create('services', function (Blueprint $table) {
            $table->integer('service_id')->primary()->autoIncrement();
            $table->integer('employee_id')->nullable();
            $table->decimal('service_price', 12, 2)->nullable();
            $table->string('service_type', 100)->nullable();
            $table->integer('office_id')->nullable();
            $table->integer('status_id')->nullable();
            
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('office_id')->references('office_id')->on('locations');
            $table->foreign('status_id')->references('status_id')->on('service_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
