<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            "full_name" => $this->first_name . ' ' . $this->last_name,
            "address" => $this->address,
            "phone" => $this->phone

        ];
    }
}
