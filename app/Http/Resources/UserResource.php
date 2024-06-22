<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $extra;

    public function __construct($resource, $extra = null)
    {
        parent::__construct($resource);
        $this->extra = $extra;
    }
    public function toArray(Request $request): array
    {
        $userArray = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'role' => $this->role,
        ];

        if (is_array($this->extra)) {
            $userArray = array_merge($userArray, $this->extra);
        }

        return $userArray;
    }
}
