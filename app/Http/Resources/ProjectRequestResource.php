<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectRequestResource extends JsonResource
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
            'isLookingForMembers' => $this->is_looking_for_members ?? false,
            'maxNumber' => $this->max_number ?? null,
            'team' => TeamResource::make($this->whenLoaded('team')),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'createdAt' => $this->created_at?->format('Y-m-d'),
            'updatedAt' => $this->updated_at?->format('Y-m-d')
        ];
    }
}
