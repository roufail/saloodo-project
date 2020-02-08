<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_item_id','count'];
    //
    public function product() {
      return $this->hasOne(Product::class,'id');
    }
}
