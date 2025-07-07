<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBatchRequest;
use App\Models\ProductionBatch;
use App\Models\Product;

class ProductionBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ProductionBatch::with('product');

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('batch_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if (request('status_filter')) {
            $query->where('status', request('status_filter'));
        }

        // Quantity range filter
        if (request('quantity_filter')) {
            switch (request('quantity_filter')) {
                case 'small':
                    $query->where('quantity', '<', 100);
                    break;
                case 'medium':
                    $query->whereBetween('quantity', [100, 500]);
                    break;
                case 'large':
                    $query->where('quantity', '>', 500);
                    break;
            }
        }

        $productionBatches = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('production_batches.index', compact('productionBatches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('production_batches.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductionBatchRequest $request)
    {
        ProductionBatch::create($request->validated());
        return redirect()->route('production-batches.index')->with('success', 'Production batch created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionBatch $productionBatch)
    {
        $productionBatch->load('product');
        return view('production_batches.show', compact('productionBatch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionBatch $productionBatch)
    {
        $products = Product::all();
        return view('production_batches.edit', compact('productionBatch', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductionBatchRequest $request, ProductionBatch $productionBatch)
    {
        $productionBatch->update($request->validated());
        return redirect()->route('production-batches.index')->with('success', 'Production batch updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionBatch $productionBatch)
    {
        $productionBatch->delete();
        return redirect()->route('production-batches.index')->with('success', 'Production batch deleted successfully!');
    }
}
