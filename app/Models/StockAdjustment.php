<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'event_stock_id',
        'amount',
        'adjustment_reason'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function eventStock()
    {
        return $this->hasOne(EventStock::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
