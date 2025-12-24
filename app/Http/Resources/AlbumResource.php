<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
 use Illuminate\Support\Facades\Storage;
 
 class AlbumResource extends JsonResource
 {
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'title' => $this->title,
             'slug' => $this->slug,
             'description' => $this->description,
             'cover_image' => $this->cover_image ? asset(Storage::url($this->cover_image)) : null,
             'photos_count' => $this->whenCounted('photos'),
             'batch' => new BatchResource($this->whenLoaded('batch')),
             'activity' => $this->when(
                 $this->relationLoaded('activity') && $this->activity,
                 fn () => [
                     'id' => $this->activity->id,
                     'title' => $this->activity->title,
                     'slug' => $this->activity->slug,
                 ]
             ),
             'photos' => PhotoResource::collection($this->whenLoaded('photos')),
         ];
     }
 }
