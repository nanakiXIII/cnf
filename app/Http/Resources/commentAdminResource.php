<?php

namespace App\Http\Resources;

use App\post;
use Illuminate\Http\Resources\Json\JsonResource;

class commentAdminResource extends JsonResource
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
            'news' => post::find($this->commentable_id),
            'user' => miniUserResource::make($this->commentator),
            'created_at' => $this->created_at

        ];
    }
}
