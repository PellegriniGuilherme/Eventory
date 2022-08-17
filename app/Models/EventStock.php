<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'event_id',
        'amount',
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

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function stockAdjustment()
    {
        return $this->belongsToMany(StockAdjustment::class);
    }

}
