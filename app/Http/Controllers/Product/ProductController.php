<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && trim($request->search) !== '') {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $query->latest()->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
    $similar = Product::where('id', '!=', $product->id)->latest()->take(4)->get();

    return view('products.show', compact('product', 'similar'));
    }

    public function adminIndex()
    {
    $products = Product::latest()->paginate(10);

    return view('admin.products.index', compact('products'));
    }

    public function create()
    {
    return view('admin.products.create');
    }

   public function store(Request $request)
   {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = basename($path);
    }

    Product::create($data);

    return redirect()->route('admin.products.index')->with('success', 'Product created!');
    }

    public function edit(Product $product)
    {
    return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = basename($path);
    }

    $product->update($data);

    return redirect()->route('admin.products.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
    $product->delete();
    return back()->with('success', 'Product deleted!');
    }


}
