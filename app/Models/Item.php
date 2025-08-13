<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';
    public $timestamps = false;

    protected $fillable = [
        'item_name',
        'item_type',
        'status'
    ];

    // Relationship ke transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'item_id', 'item_id');
    }
}
