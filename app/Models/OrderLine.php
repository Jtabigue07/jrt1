<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    public $timestamps = false; // Disable timestamps

    protected $table = 'orderline';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'user_id', 'total_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderInfo::class, 'order_id');
    }
}