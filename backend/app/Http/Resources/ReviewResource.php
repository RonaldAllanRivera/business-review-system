<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_id' => $this->business_id ?? null,
            'user_id' => $this->user_id ?? null,
            'rating' => $this->rating ?? null,
            'title' => $this->title ?? null,
            'body' => $this->body ?? null,
            'status' => $this->status ?? null,
            'moderated_by' => $this->moderated_by ?? null,
            'moderated_at' => $this->moderated_at ?? null,
            'rejection_reason' => $this->rejection_reason ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
        ];
    }
}
