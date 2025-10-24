<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where("created_by", Auth::id())->paginate(15);
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
        return view('dashboard.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::id();
    
        if ($request->hasFile('image_url')) {
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            
            $imagePath = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $imagePath;
        } 
      
        elseif ($request->has('remove_image') && $request->remove_image == '1') {
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $data['image_url'] = null;
        }
        // If no new image and no removal request, keep the existing image
        else {
            $data['image_url'] = $product->image_url;
        }
    
        $product->update($data);
    
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }


    
}
