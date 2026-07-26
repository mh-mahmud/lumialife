<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'invoice_no',
        'customer_name',
        'mobile_number',
        'area',
        'address',
        'product_code',
        'product_name',
        'product_color',
        'product_size',
        'unit_price',
        'quantity',
        'total_price',
        'sub_total',
        'shipping_charge',
        'payable_amount',
        'discount',
        'status',
        'order_date',
        'sms_response',
        'assigned_agent_id',
        'steadfast_consignment_id',
        'steadfast_tracking_code',
        'steadfast_status',
        'steadfast_response'
    ];

    protected $casts = ['steadfast_response' => 'array'];

   
    /*public function setTotalPriceAttribute()
    {
        $this->attributes['total_price'] = $this->attributes['unit_price'] * $this->attributes['quantity'];
    }*/

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class, 'billing_address_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(Agent::class, 'assigned_agent_id', 'agent_id');
    }
}
