<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
 
 class TimelineEventResource extends JsonResource
 {
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'year' => $this->year,
             'title' => $this->title,
             'description' => $this->description,
             'icon' => $this->icon,
             'order' => $this->order,
         ];
     }
 }
