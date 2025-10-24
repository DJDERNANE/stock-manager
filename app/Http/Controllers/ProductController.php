<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\RestockProductRequest;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();

            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('products', 'public');
                $data['image_url'] = $imagePath;
            }

            $product = Product::create($data);

            if ($product->quantity > 0) {
                StockHistory::create([
                    'product_id' => $product->id,
                    'adjustment_type' => StockHistory::TYPE_PURCHASE,
                    'quantity_before' => 0,
                    'quantity_change' => $product->quantity,
                    'quantity_after' => $product->quantity,
                    'changed_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
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
        } elseif ($request->has('remove_image') && $request->remove_image == '1') {
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


    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:' . implode(',', array_keys(StockHistory::getAdjustmentTypes())),
            'quantity_change' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $quantityBefore = $product->quantity;
            $adjustmentType = $request->adjustment_type;
            $quantityInput = abs($request->quantity_change);

            // Determine if this is an increase or decrease
            $isIncrease = in_array($adjustmentType, StockHistory::getIncreaseTypes());
            $quantityChange = $isIncrease ? $quantityInput : -$quantityInput;
            $quantityAfter = $quantityBefore + $quantityChange;

            // Prevent negative stock
            if ($quantityAfter < 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient stock. Cannot reduce by ' . $quantityInput . ' units.');
            }

            // Create stock history record
            StockHistory::create([
                'product_id' => $product->id,
                'adjustment_type' => $adjustmentType,
                'quantity_before' => $quantityBefore,
                'quantity_change' => $quantityChange,
                'quantity_after' => $quantityAfter,
                'changed_by' => Auth::id(),
            ]);

            // Update product quantity
            $product->update([
                'quantity' => $quantityAfter,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            $adjustmentLabel = StockHistory::getAdjustmentTypes()[$adjustmentType] ?? $adjustmentType;
            $message = $isIncrease
                ? "Stock increased by {$quantityInput} units ({$adjustmentLabel}) successfully!"
                : "Stock reduced by {$quantityInput} units ({$adjustmentLabel}) successfully!";

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }

    public function history(Product $product)
    {
        $stockHistory = StockHistory::where('product_id', $product->id)
            ->with('adjuster')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.products.history', compact('product', 'stockHistory'));
    }
}
