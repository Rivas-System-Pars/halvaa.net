<?php

namespace App\Http\Resources\Api\V1\User;

use App\Http\Resources\Api\V1\Address\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'profile_photo_url' => 'http://127.0.0.1:8000' . '/' . $this->profileImage->image,
            'bio' => $this->bio,
            'is_private' => $this->is_private,
            'death_city_id' => $this->death_city_id,
            'birth_city_id' => $this->birth_city_id,
            'birth' => $this->birth,
            'death' => $this->death,

        ];
    }
}
