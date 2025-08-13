<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'service_price',
        'service_type',
        'office_id',
        'status_id'
    ];

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

    // Relationship ke service status
    public function serviceStatus()
    {
        return $this->belongsTo(ServiceStatus::class, 'status_id', 'status_id');
    }
}
