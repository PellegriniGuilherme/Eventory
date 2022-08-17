<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'store_id',
        'user_id',
        'cancellation_reason'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function event()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function closeSale()
    {
        return $this->belongsTo(CloseSale::class)->with('payment');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_sales');
    }
}
