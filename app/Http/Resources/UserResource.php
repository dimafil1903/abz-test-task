<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @var User
     */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'position' => $this->resource->position?->name,
            'position_id' => $this->resource->position_id,
            'photo' => $this->resource->photo,
        ];
    }
}

