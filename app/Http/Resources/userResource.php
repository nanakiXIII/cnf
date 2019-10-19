<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class userResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tab = [];
        foreach ($request->user()->roles()->get() as $roles){
            foreach ($roles->permissions()->pluck('name') as $perm){
                if (!in_array($perm, $tab)){
                    $tab[] =  $perm;
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'theme' => $this->theme,
            'avatar' => $this->avatar,
            'notification' => $this->notification,
            'team' => $this->team,
            'postes' => $this->postes,
            'equipe' => $this->equipe,
            'role' => $this->roles()->pluck('name'),
            'permission' => $tab,
            'telechargement' => downloadResource::collection($this->download()->where('user_id', $this->id)->where('qualite', 'hd')->orWhere('qualite', 'dvd')->orWhere("qualite", 'fhd')->orderBy('id', 'DESC')->get()),
            'visionnage' => downloadResource::collection($this->download()->where('qualite', 'vue')->orderBy('id', 'DESC')->get()) ,
            'lecture' => downloadResource::collection($this->download()->where('qualite', 'lu')->orderBy('id', 'DESC')->get())  ,
            'suivis' => serieOnlyResource::collection($this->series) ,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at

        ];
    }
}

