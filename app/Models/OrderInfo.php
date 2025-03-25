<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    use HasFactory;

    public $timestamps = false; // Disable timestamps

    protected $table = 'orderinfo';
    protected $primaryKey = 'orderinfo_id';
    protected $fillable = [
        'order_id', 'item_id', 'quantity', 'unit_price', 'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(OrderLine::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}