<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Registration successful',
            'data' => new UserResource($this->resource),
        ];
    }
}
