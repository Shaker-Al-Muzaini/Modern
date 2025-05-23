<?php
namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getPaginatedProducts()
    {
        return Product::with(['brand:id,name', 'category:id,name', 'product_images:id,product_id,image'])
            ->select('id', 'title', 'brand_id','quantity', 'price','category_id', 'slug')
            ->latest()
            ->paginate(10);
    }
}
