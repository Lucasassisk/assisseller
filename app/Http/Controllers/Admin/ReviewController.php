<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $pending  = Review::with('product')->where('approved', false)->latest()->get();
        $approved = Review::with('product')->where('approved', true)->latest()->get();
        return view('admin.reviews.index', compact('pending', 'approved'));
    }

    public function approve(Review $review)
    {
        $review->update(['approved' => true]);
        return back()->with('success', 'Avaliação aprovada e publicada na loja!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Avaliação removida.');
    }
}
