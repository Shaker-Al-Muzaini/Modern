<?php
namespace App\Repositories;

use App\Models\Brand;

class BrandRepository
{
    public function all()
    {
        return Brand::select('id', 'name')->get();
    }
}
