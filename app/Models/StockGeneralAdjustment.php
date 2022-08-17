<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockGeneralAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'stock_general_id',
        'amount',
        'adjustment_reason'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function stockGeneral()
    {
        return $this->hasOne(StockGeneral::class, 'stock_general_id');
    }
}
