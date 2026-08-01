<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Login successful',
            'data' => new UserResource($this->resource),
        ];
    }
}
