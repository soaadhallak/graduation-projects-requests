<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JoinRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'status' => $this->status,
            'user' => UserResource::make($this->whenLoaded('user')),
            'team' => TeamResource::make($this->whenLoaded('team')),
            'projectRequest' => ProjectRequestResource::make($this->whenLoaded('projectRequest'))
        ];
    }
}
