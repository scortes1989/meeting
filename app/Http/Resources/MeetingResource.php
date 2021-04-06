<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'number'        => $this->getNumber(),
            'name'          => $this->name,
            'description'   => $this->description,
            'created_at'    => $this->created_at ? $this->created_at->format('d/m/Y H:i') : null,
            'date'          => $this->date ? Carbon::parse($this->date)->format('d/m/Y') : null,
        ];
    }
}