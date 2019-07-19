<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodesAdminResource extends JsonResource
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
            'dvd' => $this->dvd,
            'hd' => $this->hd,
            'fhd' => $this->fhd,
            'image' => $this->image,
            'serie_id' => $this->serie_id,
            'saisons_id' => $this->saisons_id,
            'etat' => $this->etat,
            'streaming' => $this->streaming,
            'publication' => $this->publication
        ];
    }
}
