<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        return [
            "id" => $this->id,
            "title" => $this->title,
            "cover" => url(\Storage::url($this->cover)),
            "price" => $this->price,
            "count" => $this->count,
            "total" => $this->total
        ];

        //return parent::toArray($request);
    }
}
