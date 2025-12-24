<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
 use Illuminate\Http\Resources\Json\JsonResource;
 use Illuminate\Support\Facades\Storage;
 
 class PhotoResource extends JsonResource
 {
     public function toArray(Request $request): array
     {
         return [
             'id' => $this->id,
             'image_path' => asset(Storage::url($this->image_path)),
             'caption' => $this->caption,
         ];
     }
 }
