<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'user' => new User($this->user),
            'guest' => new Guest($this->guest),
            'currency' => $this->currency,
            'status' => $this->status,
            'delivery_fee' => $this->delivery_fee,
            'amount' => $this->amount,
            'delivery_time' => Carbon::parse($this->delivery_time)->format('m-d H:i'),
            'items' => OrderItem::collection($this->items),
        ];
    }
}
