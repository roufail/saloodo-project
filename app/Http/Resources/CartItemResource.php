<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\ProductRepository;
class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subtotal = calculateDiscount($this->product->price,$this->product->discount_type,$this->product->discount_amount);
        $item =  [
            'id'                => $this->product->id,
            'title'             => $this->product->title,
            'price'             => $this->product->price,
            "cover"             => $this->product->cover ? url(\Storage::url($this->product->cover->url)) : null,
            'count'             => $this->count,
            "discount"          => calculateAmount($this->product->price,$this->product->discount_type,$this->product->discount_amount),
            "sub_total"         => $subtotal,
            "grand_total"       => $subtotal * $this->count,
        ];
        return $item;
    }


}
