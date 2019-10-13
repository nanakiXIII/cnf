<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'type' => $this->type,
            'staff' => $this->staff,
            'image' => $this->image,
            'slug' => $this->slug,
            'user' => [ 'name' => $this->user->name, 'avatar' => $this->user->avatar ],
            'serie_id' => $this->serie_id,
            'publication' => $this->publication,
            'publish_at' => $this->publish_at,
            'fichier_id' => $this->fichiers()->pluck('episodes_id'),
            'fichiers' => FichiersNewsResource::collection($this->fichiers()->where('publication', 1)->orderBy('numero', 'ASC')->get()),
            'comments' => commentsResource::collection($this->comments()->orderBy('id', 'DESC')->approved()->get())
        ];
    }
}
