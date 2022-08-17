<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloseSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sale_id',
        'payment_id',
        'total',
        'total_tax',
        'total_profit',
        'discount',
        'discount_reason'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'id', 'sale_id');
    }

    public function payment()
    {
        return $this->haOne(PaymentMethod::class, 'id', 'payment_id');
    }
}
