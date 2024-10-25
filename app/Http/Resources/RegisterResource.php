<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $details = [
            'token_type' => 'Bearer',
            'authorization' => $this->token,
            'user' => [
                'id' => $this->id, // Return specific user attributes
                'name' => $this->name,
                'email' => $this->email,
                // Add any other user attributes you want to include
            ],
        ];
        return $details;
    }
}
