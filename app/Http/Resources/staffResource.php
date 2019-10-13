<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class staffResource extends JsonResource
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
            'pseudo' => $this->name,
            'avatar' => $this->avatar,
            'postes' => $this->competence,
            'created_at' => $this->created_at,
        ];
    }
}

