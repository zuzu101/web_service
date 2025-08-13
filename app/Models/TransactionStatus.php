<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    protected $table = 'transaction_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $fillable = [
        'status_name'
    ];

    // Relationship ke transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'status_id', 'status_id');
    }
}
