<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamInvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'team'=>TeamResource::make($this->whenLoaded('team')),
            'email'=> $this->email,
            'status'=>$this->status,
            'user'=>UserResource::make($this->whenLoaded('user')),
            'expiresAt'=>$this->expires_at?->format('Y-m-d H:i'),
            'createdAt'=>$this->created_at?->diffForHumans(),
        ];
    }
}
