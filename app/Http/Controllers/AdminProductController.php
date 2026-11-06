<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('user')->orderByRaw("CASE WHEN status = 'Pending' THEN 1 WHEN status = 'Under Verification' THEN 2 ELSE 3 END")->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|in:Pending,Under Verification,Approved,Rejected',
            'moderation_notes' => 'nullable|string'
        ]);

        $product->update($request->only(['status', 'moderation_notes']));

        return redirect()->route('admin.products.index')->with('success', 'Product status updated successfully.');
    }
}
