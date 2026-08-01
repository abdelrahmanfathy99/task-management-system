<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->userId,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'token' => $this->resource->token,
        ];
    }
}
