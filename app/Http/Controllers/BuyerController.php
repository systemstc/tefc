<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExporterProfile;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function sellerDirectory(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('slug', 'seller');
        })->where('status', 'active')->with('exporterProfile');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('exporterProfile', function($pq) use ($search) {
                      $pq->where('company_name', 'like', "%{$search}%")
                        ->orWhere('hs_codes', 'like', "%{$search}%")
                        ->orWhere('profile_description', 'like', "%{$search}%");
                  });
            });
        }

        $sellers = $query->paginate(10);

        return view('buyer.sellers', compact('sellers'));
    }

    public function toggleSaveSeller(Request $request, User $seller)
    {
        $buyer = Auth::user();
        
        if ($buyer->savedSellers()->where('seller_id', $seller->id)->exists()) {
            $buyer->savedSellers()->detach($seller->id);
            return redirect()->back()->with('success', 'Seller removed from favorites.');
        } else {
            $buyer->savedSellers()->attach($seller->id);
            return redirect()->back()->with('success', 'Seller saved to favorites!');
        }
    }

    public function savedSellers()
    {
        $sellers = Auth::user()->savedSellers()->with('exporterProfile')->paginate(10);
        return view('buyer.saved_sellers', compact('sellers'));
    }

    public function myInquiries()
    {
        $inquiries = \App\Models\ProductInquiry::where('user_id', Auth::id())
            ->with(['product.user.exporterProfile'])
            ->latest()
            ->paginate(15);

        return view('buyer.inquiries.index', compact('inquiries'));
    }
}
