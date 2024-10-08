<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItems extends Model
{
    use HasFactory;

    protected $table = 'request_items';

    // Accessor untuk memformat ID menjadi 3 digit
    public function getFormattedIdAttribute()
    {
        return str_pad($this->attributes['id'], 3, '0', STR_PAD_LEFT);
    }

    protected $fillable = [
        'user_id',
        'product_id',
        'stock',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItems::class, 'request_id');
    }
}
