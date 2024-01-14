<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"  => encrypt($this->id),
            "normal_id" => $this->id,
            "name"  => $this->name,
            "remark" => $this->remark,
            "parent_name" => !is_null($this->parentCategory) ?  $this->parentCategory->name : "-"
        ];
    }
}
