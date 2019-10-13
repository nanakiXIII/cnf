<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MembreAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permission = [];
        foreach ($this->roles()->get() as $roles){
            foreach ($roles->permissions()->pluck('name') as $perm){
                if (!in_array($perm, $permission)){
                    $tab[] =  $perm;
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'theme' => $this->theme,
            'avatar' => $this->avatar,
            'equipe' => $this->equipe,
            'posteID' => $this->competence()->pluck('postes_id'),
            'poste' => $this->competence,
            'permission' => $permission,
            'role' => $this->roles()->pluck('name'),
            'roleID' => $this->roles()->pluck('id'),
        ];
    }
}
