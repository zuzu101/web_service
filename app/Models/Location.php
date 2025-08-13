<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'office_id';
    public $timestamps = false;

    protected $fillable = [
        'office_address'
    ];

    // Relationship ke services
    public function services()
    {
        return $this->hasMany(Service::class, 'office_id', 'office_id');
    }

    // Relationship ke transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'office_id', 'office_id');
    }
}
