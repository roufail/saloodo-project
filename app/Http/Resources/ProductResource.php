<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductPhotosResource;
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $product = [
            "id"                => $this->id,
            "title"             => $this->title,
            "description"       => $this->description,
            "price"             => $this->price,
            "discount_amount"   => $this->discount_amount,
            "discount_type"     => $this->discount_type,
            "discount"          => calculateAmount($this->price,$this->discount_type,$this->discount_amount),
            "subtotal"          => calculateDiscount($this->price,$this->discount_type,$this->discount_amount),
            "cover"             => $this->cover ? url(\Storage::url($this->cover->url)) : null,
            "photos"            => ProductPhotosResource::collection($this->photos),
        ];

        if($this->getChilds()) {
            $product['childs'] = ProductResource::collection($this->getChilds());
        }
        return $product;
    }


}
