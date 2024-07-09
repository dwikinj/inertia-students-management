<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->formatDate($this->created_at),
            'class' => ClassesResource::make($this->whenLoaded('class')),
            'section' => SectionResource::make($this->whenLoaded('section')),
        ];
    }

    //format date to indonesia format
    private function formatDate($date){
        if (!$date) {
            return null;
        }

        Carbon::setLocale('id');
        return Carbon::parse($date)->translatedFormat('d F Y');
    }
}
