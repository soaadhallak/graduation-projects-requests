<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;



class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role'=>$this->whenLoaded('roles',fn()=>$this->getRoleNames()->first()),
            'permissions'=>$this->whenLoaded('permissions',fn()=>$this->getAllPermissions()->pluck('name')),
            'avatar' => $this->whenLoaded('media',function(){
                $media = $this->getMedia('primary-image')->first();
                return $media ? MediaResource::make($media) : null;
            }),
            'studentDetailes'=>StudentResource::make($this->whenLoaded('student')),
        ];
    }
}
