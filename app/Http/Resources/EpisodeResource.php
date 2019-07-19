<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->dvd == 'non'){
            $dvd = $this->dvd;
        }else{
            $dvd = env('URL_DL').$this->dvd;
        }
        if ($this->hd == 'non'){
            $hd = $this->hd;
        }else{
            $hd = env('URL_DL').$this->hd;
        }
        if ($this->fhd == 'non'){
            $fhd = $this->fhd;
        }else{
            $fhd = env('URL_DL').$this->fhd;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'numero' => $this->numero,
            'type' => $this->type,
            'dvd' => $dvd,
            'hd' => $hd,
            'fhd' => $fhd,
            'image' => $this->image,
            'serie_id' => $this->serie_id,
            'saisons_id' => $this->saisons_id,
            'etat' => $this->etat,
            'streaming' => $this->streaming,
            'publication' => $this->publication
        ];
    }
}
