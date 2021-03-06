<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaisonResource extends JsonResource
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
            'name' => $this->name,
            'numero' => $this->numero,
            'type' => $this->type,
            'serie_id' => $this->serie_id,
            'publication' => $this->publication,
            'nosaison' => $this->nosaison,
            'episodes' => EpisodeResource::collection($this->episodes()->where('publication', '1')->orderBy("numero", 'ASC')->get()),
            'episode' => count($this->episodes)

        ];
    }
}
