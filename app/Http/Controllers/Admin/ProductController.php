<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderByDesc('id')->get();
        $globalSizes = array_filter(array_map('trim', explode(',', Setting::get('product_sizes_global', '33-34,35-36,37-38,39-40,41-42,43-44'))));
        return view('admin.products.index', compact('products', 'globalSizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0|max:99999',
            'badge'       => 'nullable|string|max:50',
            'sizes'       => 'nullable|string|max:500',
            'stock'       => 'nullable|integer|min:0|max:99999',
            'image_url'   => 'nullable|url|regex:/^https?:\/\//i|max:2048',
            'image'       => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $data['sizes'] = $request->sizes
            ? array_values(array_filter(array_map('trim', explode(',', $request->sizes))))
            : array_values(array_filter(array_map('trim', explode(',', Setting::get('product_sizes_global', '33-34,35-36,37-38,39-40,41-42,43-44')))));
        $data['active'] = true;
        unset($data['image_url'], $data['image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $data['image_url'] = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $data['image_url'] = $request->image_url;
        }

        $product = Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0|max:99999',
            'badge'       => 'nullable|string|max:50',
            'sizes'       => 'nullable|string|max:500',
            'stock'       => 'nullable|integer|min:0|max:99999',
            'active'      => 'boolean',
            'image_url'   => 'nullable|url|regex:/^https?:\/\//i|max:2048',
            'image'       => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        if (isset($data['sizes'])) {
            $data['sizes'] = array_values(array_filter(array_map('trim', explode(',', $data['sizes']))));
        }

        unset($data['image_url'], $data['image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $data['image_url'] = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $data['image_url'] = $request->image_url;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado!');
    }

    public function toggle(Product $product)
    {
        $product->update(['active' => !$product->active]);
        return back()->with('success', $product->active ? 'Produto ativado.' : 'Produto desativado.');
    }

    public function destroy(Product $product)
    {
        $product->update(['active' => false]);
        return back()->with('success', 'Produto desativado.');
    }
}
