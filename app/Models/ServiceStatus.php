<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStatus extends Model
{
    protected $table = 'service_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $fillable = [
        'status_name'
    ];

    // Relationship ke services
    public function services()
    {
        return $this->hasMany(Service::class, 'status_id', 'status_id');
    }
}
