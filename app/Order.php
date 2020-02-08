<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','total_amount','products_count'];
    //
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
