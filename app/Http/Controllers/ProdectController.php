<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_images;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProdectController extends Controller
{

    public function index()
    {
        $Products = Product::with([
            'brand:id,name',
            'category:id,name',
            'product_images:id,product_id,image',
        ])
            ->select('id', 'title', 'brand_id','quantity', 'price','category_id', 'slug')
            ->latest()
            ->paginate(10);

        $brands = Brand::select('id', 'name')->get();
        $categories =Category::select('id','name')->get();

        return Inertia::render('Admin/Product/index', [
            'Products'=>$Products,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {


        $product = Product::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'published' => $request->published ?? 0,
            'inStock' => $request->inStock ?? 0,
            'price' => $request->price,
            'quantity' => $request->quantity ?? 0,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);


        //check if product has images upload

        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('product_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                Product_images::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }


        return redirect()->route('admin.product.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'published' => $request->published ?? 0,
            'inStock' => $request->inStock ?? 0,
        ]);

        $this->handleImages($request, $product->id);

        return redirect()->route('admin.product.index')->with('success', 'Product updated successfully.');
    }

    protected function handleImages(Request $request, $productId)
    {
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move('product_images', $uniqueName);

                Product_images::create([
                    'product_id' => $productId,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }
    }

    public function deleteImage($id)
    {
        $image = Product_images::findOrFail($id);
        if (file_exists(public_path($image->image))) {
            unlink(public_path($image->image));
        }
        $image->delete();

        return redirect()->route('admin.product.index')->with('success', 'Image deleted successfully.');
    }

    public function destory($id)
    {
        $product = Product::findOrFail($id);
        foreach ($product->product_images as $image) {
            if (file_exists(public_path($image->image))) {
                unlink(public_path($image->image));
            }
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully.');
    }

    //update





}
