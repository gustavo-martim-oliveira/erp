<?php

namespace App\Http\Resources\Central;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'email' => $this->email,

            'avatar' => $this->avatar,

            'is_active' => $this->is_active,

            'role' => optional($this->roles->first())->name,

            'role_label' => optional($this->roles->first())->name ?? '-',

            'created_at' => optional($this->created_at)->format('d/m/Y H:i'),

            'updated_at' => optional($this->updated_at)->format('d/m/Y H:i'),
        ];
    }
}