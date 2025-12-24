<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
 
 class BatchResource extends JsonResource
 {
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'name' => $this->name,
             'year' => $this->year,
             'is_active' => $this->when($request->routeIs('*.index'), $this->is_active),
         ];
     }
 }
