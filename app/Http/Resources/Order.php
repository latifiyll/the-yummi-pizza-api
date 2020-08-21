<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'order_number' => $this->order_no,
            'user' => $this->customer,
            'guest' => new Guest($this->guest),
            'currency' => $this->currency,
            'status' => $this->status,
            'delivery_fee' => $this->delivery_fee,
            'amount' => $this->amount,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'items' => OrderItem::collection($this->items),
        ];
    }
}
