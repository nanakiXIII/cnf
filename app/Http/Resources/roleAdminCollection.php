<?php

namespace App\Http\Resources;

use App\postes;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class roleAdminCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'statut' => 200,
            'data' => roleAdminResource::collection($this->collection),
            'permissions' => Permission::all()
        ];
    }
}
