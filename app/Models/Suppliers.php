<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'email', 'phone'];

	protected $hidden = ['created_at', 'updated_at'];

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
}
