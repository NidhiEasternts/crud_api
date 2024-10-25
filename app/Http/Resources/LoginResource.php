<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $userDetails = $request->user();
        return [
            'message' => config('constants.messages.login.success'),
            'authorization' => $this->authorization,
            'refresh_token' => $this->refresh_token,
            'loginUId' => $this->id,
            'name' => $userDetails->name ?? '',
            'email' => $this->email,
        ];
    }
}
