<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
 use Illuminate\Support\Facades\Storage;
 
 class OrganizationMemberResource extends JsonResource
 {
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'position' => $this->position,
             'name' => $this->name,
             'photo' => $this->photo ? asset(Storage::url($this->photo)) : null,
             'description' => $this->description,
             'level' => $this->level,
             'order' => $this->order,
             'batch' => new BatchResource($this->whenLoaded('batch')),
         ];
     }
 }
