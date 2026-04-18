<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => (float) $this->price,
            'purchase_price' => (float) $this->purchase_price,
            'selling_price' => (float) $this->selling_price,
            'margin' => (float) ($this->margin ?? 1.3),
            'quantity' => (int) $this->quantity,
            'min_stock' => (int) $this->min_stock,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'status' => $this->isLowStock() ? 'low_stock' : 'in_stock',
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category ? $this->category->name : null,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
