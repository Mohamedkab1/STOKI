<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $fifoService;

    public function __construct(FIFOService $fifoService)
    {
        $this->fifoService = $fifoService;
    }

    /**
     * Create a new product and initialize stock if required.
     */
    public function createProduct(array $data, $imageFile = null): Product
    {
        return DB::transaction(function () use ($data, $imageFile) {
            if ($imageFile) {
                $data['image'] = $imageFile->store('products', 'public');
            }

            $initialQuantity = $data['quantity'] ?? 0;
            $data['quantity'] = 0; // Will be incremented via FIFOService

            $product = Product::create($data);

            if ($initialQuantity > 0) {
                $this->fifoService->recordPurchase($product, $initialQuantity, $product->price, [
                    'reason' => 'Stock initial',
                    'supplier' => 'Stock Initial'
                ]);
            }

            return $product;
        });
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data, $imageFile = null): Product
    {
        return DB::transaction(function () use ($product, $data, $imageFile) {
            if ($imageFile) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $imageFile->store('products', 'public');
            }

            $product->update($data);

            return $product;
        });
    }

    /**
     * Delete a product safely.
     */
    public function deleteProduct(Product $product): bool
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        return $product->delete();
    }
}
