<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product_images extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',];
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
