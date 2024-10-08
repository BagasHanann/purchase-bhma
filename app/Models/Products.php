<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = ['categories_id','name', 'description', 'price', 'stock'];

    protected $hidden = ['created_at','updated_at'];

    public function categories()
    {
        return $this->belongsTo(Categories::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItems::class);
    }
}
