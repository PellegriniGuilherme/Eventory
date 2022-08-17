<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_id',
        'tax',
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}
