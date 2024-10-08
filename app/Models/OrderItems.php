<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    protected $fillable = [
        'request_id',
        'product_id',
        'supplier_id', 
        'price', 
        'total_price', 
        'stock', 
        'date'
    ];

    protected $hidden = ['created_at','updated_at'];

    public function requestItem()
    {
        return $this->belongsTo(RequestItems::class);
    }

    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function supplier() {
        return $this->belongsTo(Suppliers::class);
    }

    // Accessor to format request_id as three digits
    public function getRequestIdAttribute($value)
    {
        return str_pad($value, 3, '0', STR_PAD_LEFT);
    }

    // Mutator to ensure request_id is stored as integer
    public function setRequestIdAttribute($value)
    {
        $this->attributes['request_id'] = intval($value);
    }
}
