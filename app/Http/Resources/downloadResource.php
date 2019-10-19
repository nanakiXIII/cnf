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
        if ($this->episode){
            return [
                'id' => $this->id,
                'time' => $this->time,
                'user_id' => $this->user_id,
                'episode' => EpisodeResource::make($this->episode),
                'serie' => $this->serie,
                'saison' => Saisons::find($this->episode->saisons_id) ,
                'created_at' => $this->created_at
            ];
        }else{
            return [
                'episode' => ["Erreur" => true, "message" => "Fichier plus disponible"],
                'serie' => $this->serie,
                'saison' => ["Erreur" => true, "message" => "Fichier plus disponible"],
                'created_at' => $this->created_at
            ];
        }

    }
}
