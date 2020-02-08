<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;
class Cart extends Model
{
    private $total_amount = 0;
    private $items_count = 0;
    private $items_arr = [];

    protected $fillable = ['user_id'];
    //
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(CartItem::class);
    }

    public function placeOrder() {
        $this->items->map(function($item){
            $price = calculateDiscount($item->product->price,$item->product->discount_type,$item->product->discount_amount);
            $total =  $price * $item->count;
            $this->items_arr[] = [
                'product_id' => $item->product->id,
                'title' => $item->product->title,
                'cover' => $item->product->cover ? $item->product->cover->url : null,
                'price' => $price,
                'count' => $item->count,
                'total' => $total,
            ];
            $this->total_amount += $total;
            $this->items_count += $item->count;
        });

        if($this->items_count > 0) {
            $order = Order::create([
                'user_id'       => auth('customer-api')->user()->id,
                'total_amount'  => $this->total_amount,
                'products_count' => $this->items_count,
            ]);
            $order->items()->createMany($this->items_arr);
            auth()->user()->cart()->delete();
        }
        return $order;
    }
}
