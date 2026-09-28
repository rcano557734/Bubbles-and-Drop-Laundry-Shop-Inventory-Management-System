<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'service_id',
        'machine_id',
        'order_source',
        'service_number',
        'kilos',
        'load_count',
        'detergent_quantity',
        'fabric_conditioner_quantity',
        'total_amount',
        'status',
        'payment_status',
        'paid_at',
        'received_at',
        'completed_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Existing primary/legacy machine relationship.
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    // New multiple-machine relationship.
    public function machines()
    {
        return $this->belongsToMany(
            Machine::class,
            'laundry_order_machine'
        )->withTimestamps();
    }
}