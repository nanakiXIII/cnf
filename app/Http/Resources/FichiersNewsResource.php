<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FichiersNewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->user('api') && $this->download()->where('user_id', $request->user('api')->id)->where('qualite', 'dvd')->first()){
            $downloadDvd = true;
        }else{
            $downloadDvd = false;
        }
        if ($request->user('api') && $this->download()->where('user_id', $request->user('api')->id)->where('qualite', 'hd')->first()){
            $downloadHd = true;
        }else{
            $downloadHd = false;
        }
        if ($request->user('api') && $this->download()->where('user_id', $request->user('api')->id)->where('qualite', 'fhd')->first()){
            $downloadFhd = true;
        }else{
            $downloadFhd = false;
        }
        if ($request->user('api') && $visio = $this->download()->where('user_id', $request->user('api')->id)->where('qualite', 'vue')->first()){
            $stream = true;
            $time = $visio->time;
        }else{
            $stream = false;
            $time = 0;
        }
        if ($request->user('api') && $visio = $this->download()->where('user_id', $request->user('api')->id)->where('qualite', 'lu')->first()){
            $stream = true;
            $time = 0;
        }else{
            $stream = false;
            $time = 0;
        }

        if ($this->dvd == 'non'){
            $dvd = $this->dvd;
        }else{
            $dvd = env('URL_DL').$this->dvd;
        }
        if ($this->hd == 'non' || $this->type == 'Chapitre'){
            $hd = $this->hd;
            $lecture = Storage::disk('public')->files($this->streaming);
        }else{

            $hd = env('URL_DL').$this->hd;
            $lecture = null;
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
            'serie' => ['slug' => $this->serie->slug, 'type' => $this->serie->type],
            'saison' => ['numero' => $this->saison->numero, 'type' => $this->saison->type],
            'etat' => $this->etat,
            'streaming' => $this->streaming,
            'publication' => $this->publication,
            'downloaddvd' => $downloadDvd,
            'downloadhd' => $downloadHd,
            'downloadfhd' => $downloadFhd,
            'stream' => $stream,
            'time' => $time,
            'lecture' => $lecture
        ];
    }
}
