<?php

namespace App\Http\Resources;

use App\Saisons;
use Illuminate\Http\Resources\Json\JsonResource;

class downloadResource extends JsonResource
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
            'episode' => EpisodeResource::make($this->episode),
            'serie' => $this->serie,
            'saison' => Saisons::find($this->episode->saisons_id) ,
            'created_at' => $this->created_at
        ];
    }
}
