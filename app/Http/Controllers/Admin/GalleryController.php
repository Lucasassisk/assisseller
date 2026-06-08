<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $images   = Gallery::with('product')->orderByDesc('id')->get();
        $products = Product::where('active', true)->get();
        return view('admin.gallery.index', compact('images', 'products'));
    }

    public function upload(Request $request)
    {
        $request->validate(['images.*' => 'required|image|max:5120']);
        $uploaded = [];
        foreach ($request->file('images', []) as $file) {
            $path    = $file->store('uploads', 'public');
            $url     = asset('storage/' . $path);
            $gallery = Gallery::create([
                'filename'   => basename($path),
                'url'        => $url,
                'size_bytes' => $file->getSize(),
            ]);
            $uploaded[] = ['id' => $gallery->id, 'url' => $url];
        }
        return response()->json(['success' => true, 'images' => $uploaded]);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'gallery_id' => 'required|exists:gallery,id',
            'product_id' => 'required|exists:products,id',
        ]);
        $gallery = Gallery::find($request->gallery_id);
        $gallery->update(['product_id' => $request->product_id]);
        Product::find($request->product_id)->update(['image_url' => $gallery->url]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Foto atribuída ao produto!');
    }

    public function destroy(Request $request, Gallery $gallery)
    {
        $gallery->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Imagem excluída.');
    }
}
