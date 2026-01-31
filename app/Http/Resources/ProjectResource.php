<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'tools' => $this->tools,
            'grade' => $this->grade,
            'status' => $this->status,
            'supervisor' => UserResource::make($this->whenLoaded('supervisor')),
            'adminRejectionReason' => $this->admin_rejection_reason,
            'files' => MediaResource::collection($this->whenLoaded('media')),
            'createdAt' => $this->created_at?->format('Y-m-d'),
            'updatedAt' => $this->updated_at?->format('Y-m-d')
        ];
    }
}
