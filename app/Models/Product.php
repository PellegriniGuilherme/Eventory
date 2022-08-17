<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_id',
        'name',
        'description',
        'sale_price',
        'cost_price'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'product_id', 'store_id');
    }

    public function generalStock()
    {
        return $this->hasOne(StockGeneral::class, 'product_id', 'id');
    }

    public function eventStock()
    {
        return $this->hasOne(EventStock::class, 'product_id', 'id');
    }
}
