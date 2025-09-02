<?php

namespace App\Http\Resources\Datatable\Basket;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BasketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'page' => $this->currentPage(),
                'pages' => $this->lastPage(),
                'perpage' => $this->perPage(),
                'rowIds' => $this->collection->pluck('id')->toArray()
            ],
        ];
    }
}
