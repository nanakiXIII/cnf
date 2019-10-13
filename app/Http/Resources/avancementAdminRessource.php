<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class avancementAdminRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->type != 'Animes'){
            $this->edition = 100;
            $this->encodage = 100;
        }

        return [
            'id' => $this->id,
            'numero' => $this->episode,
            'serie' => serieOnlyResource::make($this->serie) ,
            'saison' => $this->saisons,
            'encodage' => $this->encodage,
            'adapt' => $this->adapt,
            'edition' => $this->edition,
            'qcheck' =>$this->qcheck,
            'traduction' => $this->traduction,
            'time' => $this->time,
            'check' => $this->check,
            'type' => $this->type,
            'user' => $this->user,
            'publication' => $this->publication,
            'created_at' => $this->created_at
        ];
    }
}
