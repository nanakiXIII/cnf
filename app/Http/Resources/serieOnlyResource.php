<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class serieOnlyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->user('api')){
            $user = $request->user('api');
            $serie = $user->series()->select('serie_id AS id', 'titre')
                ->where('publication', true)
                ->orderBy('titre', 'ASC')->pluck('id')->toArray();
            if (in_array($this->id, $serie )) {
                $abo = true;
            }else{
                $abo = false;
            }
        }else{
            $abo = false;
        }
        if ($this->titre_original == ""){
            $this->titre_original = "Null";
        }
        if ($this->titre_alternatif == ""){
            $this->titre_alternatif = "Null";
        }
        if ($this->auteur){
            $banniere =  "/storage/images/banniere/".$this->auteur;
        }
        else{
            $banniere = "";
        }
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'titre_original' => $this->titre_original,
            'titre_alternatif' => $this->titre_alternatif,
            'annee' => strtotime($this->annee),
            'studio' => $this->studio,
            'genres' => $this->genres,
            'episode' => $this->episode,
            'scan' => $this->scan,
            'synopsis' => $this->synopsis,
            'staff' => $this->staff,
            'type' => $this->type,
            'etat' => $this->etat,
            'slug' => $this->slug,
            'publication' => $this->publication,
            'image' => "/storage/images/images/".$this->image,
            'banniere' => $banniere,
            'abonnement' => count($this->users),
            'abo' => $abo
        ];
    }
}
