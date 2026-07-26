<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'order_id',
        'quantity',
        'unit_price',
        'total',
        'delivery_status',
        'order_status'
    ];

    public $timestamps = true;

    // public function orderInfo()
    // {
    //     return $this->belongsTo(OrderInfo::class, 'order_id', 'order_id');
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function products()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
