<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class commentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'user' => miniUserResource::make($this->commentator),
            'created_at' => $this->created_at

        ];
    }
}
