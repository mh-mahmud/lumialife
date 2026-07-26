<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    use HasFactory;
    protected $table = 'order_info';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'invoice_no', 
        'customer_id', 
        'mobile_number', 
        'area', 
        'contact_address',
        'sub_total', 
        'order_tax', 
        'shipping_charge', 
        'discount', 
        'payable_amount', 
        'status',
        'order_date'
    ];

    public $timestamps = true;

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}
