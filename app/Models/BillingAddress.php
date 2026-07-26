<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;
    protected $table = 'billing_address';
    protected $fillable = [
        'user_id',
        'session_id',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'mobile',
        'city',
        'state',
        'zip',
        'shipping_address',
        'shipping_address_2'
    ];
}
