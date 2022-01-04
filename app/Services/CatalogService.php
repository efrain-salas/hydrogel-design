<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CatalogService
{
    protected function http(): PendingRequest
    {
        return Http::baseUrl('https://app.titanium-shield.com/api')
            ->withHeaders([
                'Authorization' => 'Bearer 221|TEI2DRlTON8zZsj1K43utzweafgH9yf9A40tvbZv',
            ]);
    }

    public function getCategories(): array
    {
        try {
            return $this->http()->get('/catalog/categories')->json();
        } catch (\Exception $e) {
            return [];
        }

    }

    public function getSubcategories(int $categoryId): array
    {
        try {
            return $this->http()->get("/catalog/categories/$categoryId/subcategories")->json();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getProducts(int $subcategoryId): array
    {
        try {
            return $this->http()->get("catalog/subcategories/$subcategoryId/products")->json();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getPltFile(int $productId): string
    {
        return $this->http()->get("products/$productId/plotter-file", [
            'noLimit' => 1,
            'mietubl' => 1,
        ])->body();
    }
}
