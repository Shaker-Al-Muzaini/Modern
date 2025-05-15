<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_images;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProdectController extends Controller
{

    public function index()
    {
        $Products=Product::all();
        return Inertia::render('Admin/Product/index',['Products'=>$Products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product=new Product();
        $product->title=$request->title;
        $product->slug=$request->slug;
        $product->description=$request->description;
        $product->published=$request->published;
        $product->inStock=$request->inStock;
        $product->price=$request->price;
        $product->save();

        if($request->hasFile('product_images')){
            $productImages = $request->file('product_images');
            foreach($productImages as $image){
                $uniqueName = time().'_'.Str::random(10).'_'.$image->getClientOriginalName();
                $image->move('product_images', $uniqueName);
            }
            Product_images::create([
                'product_id'=>$product->id,
                'image'=>'product_images/'.$uniqueName
            ]);

        }
        return redirect()->route('admin.products.index')
            ->with('success','Product created successfully.');

    }


    /**
     * Display the specified resource.
     */
    public function show(Prodect $prodect)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodect $prodect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prodect $prodect)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodect $prodect)
    {
        //
    }
}
