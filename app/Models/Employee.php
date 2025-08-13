<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_name'
    ];

    // Relationship ke services
    public function services()
    {
        return $this->hasMany(Service::class, 'employee_id', 'employee_id');
    }

    // Relationship ke transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'employee_id', 'employee_id');
    }
}
