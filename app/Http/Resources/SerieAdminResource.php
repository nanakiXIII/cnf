<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SerieAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->titre_original == ""){
            $this->titre_original = "Null";
        }
        if ($this->titre_alternatif == ""){
            $this->titre_alternatif = "Null";
        }
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'titre_original' => $this->titre_original,
            'titre_alternatif' => $this->titre_alternatif,
            'annee' => $this->annee,
            'studio' => $this->studio,
            'genres' => $this->genres,
            'genresId' =>$this->genres()->pluck('genres_id'),
            'episode' => $this->episode,
            'scan' => $this->scan,
            'synopsis' => $this->synopsis,
            'staff' => $this->staff,
            'type' => $this->type,
            'etat' => $this->etat,
            'slug' => $this->slug,
            'publication' => $this->publication,
            'image' => $this->image,
            'banniere' => $this->auteur,
            'episodes' => count($this->episodes),
            'saisons' => SaisonAdminResource::collection($this->saisons)
        ];
    }
}
