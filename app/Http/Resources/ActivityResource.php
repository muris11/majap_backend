<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
 use Illuminate\Support\Facades\Storage;
 
 class ActivityResource extends JsonResource
 {
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'title' => $this->title,
             'slug' => $this->slug,
             'short_description' => $this->short_description,
             'content' => $this->when($request->routeIs('*.show'), $this->content),
             'cover_image' => $this->cover_image ? asset(Storage::url($this->cover_image)) : null,
             'event_date' => $this->event_date->format('Y-m-d'),
             'event_date_formatted' => $this->event_date->translatedFormat('d F Y'),
             'location' => $this->location,
             'is_featured' => $this->is_featured,
             'batch' => new BatchResource($this->whenLoaded('batch')),
         ];
     }
 }
