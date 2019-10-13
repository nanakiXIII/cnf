<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class fichierAdminResource extends JsonResource
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
            'serie' => serieOnlyResource::make($this->serie),
            'original' => Storage::disk('public')->exists("serie/$this->serie_id/$this->saisons_id/$this->id/$this->id.mkv"),
            'saison' =>$this->saison,
            'etat' => $this->etat,
            'streaming' => "/storage/serie/$this->serie_id/$this->saisons_id/$this->id/$this->id.mp4",
            'lecture' => $this->streaming,
            'publication' => $this->publication,
        ];
    }
}
