<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name',
        'status',
        'maintenance_period',
        'last_maintenance',
        'next_maintenance',
    ];

    protected $casts = [
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
    ];

    // Existing relationship through machine_id.
    public function laundryOrders()
    {
        return $this->hasMany(LaundryOrder::class);
    }

    // New multiple-machine relationship.
    public function assignedOrders()
    {
        return $this->belongsToMany(
            LaundryOrder::class,
            'laundry_order_machine'
        )->withTimestamps();
    }
}