<?php

namespace App\Http\Resources;

use App\post;
use Illuminate\Http\Resources\Json\JsonResource;

class statistiqueResource extends JsonResource
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
            'visitor' => [$this['visitors']],
            'pageView' => [$this['pageViews']],
            'date' => [$this['date']]
        ];
    }
}
