<?php
namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;

class ProductService
{
    protected $productRepo;
    protected $brandRepo;
    protected $categoryRepo;

    public function __construct(
        ProductRepository $productRepo,
        BrandRepository $brandRepo,
        CategoryRepository $categoryRepo
    ) {
        $this->productRepo = $productRepo;
        $this->brandRepo = $brandRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function getIndexData()
    {
        return [
            'Products' => $this->productRepo->getPaginatedProducts(),
            'brands' => $this->brandRepo->all(),
            'categories' => $this->categoryRepo->all(),
        ];
    }
}
