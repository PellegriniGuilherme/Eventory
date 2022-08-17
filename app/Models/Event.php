<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_id',
        'name',
        'cost',
        'localization',
        'organizer_contact',
        'organizer_name'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function dates()
    {
        return $this->hasMany(EventDate::class, 'event_id', 'id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function stocks()
    {
        return $this->hasMany(EventStock::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class)->with('closeSale')->with('products');
    }
}
