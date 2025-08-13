<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'employee_id',
        'office_id', // Ini yang benar sesuai migration
        'item_id',
        'total_price',
        'status_id', // Ini yang benar sesuai migration
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'total_price' => 'decimal:2'
    ];

    // Relationship ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship ke employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // Relationship ke location
    public function location()
    {
        return $this->belongsTo(Location::class, 'office_id', 'office_id');
    }

    // Relationship ke item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    // Relationship ke transaction status
    public function transactionStatus()
    {
        return $this->belongsTo(TransactionStatus::class, 'status_id', 'status_id');
    }
}
