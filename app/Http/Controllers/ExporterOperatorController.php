<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ExporterOperatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->isMainExporter()) {
            abort(403, 'Unauthorized. Only verified Exporter Higher Authorities can manage Data Entry Operators.');
        }

        $operators = $user->operators()
            ->withCount('createdProducts')
            ->latest()
            ->get();

        $pendingReviewsCount = Product::where('user_id', $user->id)
            ->where('status', 'Pending Exporter Review')
            ->count();

        return view('exporter.operators.index', compact('operators', 'pendingReviewsCount', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isMainExporter()) {
            abort(403, 'Unauthorized. Only verified Exporter Higher Authorities can add Data Entry Operators.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
        ]);

        $operator = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'parent_id' => $user->id,
            'tefc_centre' => $user->tefc_centre,
            'designation' => $request->designation ?: 'Data Entry Operator',
            'phone' => $request->phone,
            'status' => 'active', // Pre-approved by main exporter
        ]);

        $operatorRole = Role::where('slug', 'operator')->first();
        if ($operatorRole) {
            $operator->roles()->attach($operatorRole);
        }

        return redirect()->route('exporter.operators.index')
            ->with('success', "Data Entry Operator \"{$operator->name}\" created successfully! They can now log in to input products.");
    }

    public function toggleStatus(User $operator)
    {
        $user = Auth::user();
        if (!$user->isMainExporter() || $operator->parent_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $newStatus = $operator->status === 'active' ? 'inactive' : 'active';
        $operator->update(['status' => $newStatus]);

        return back()->with('success', "Operator \"{$operator->name}\" is now {$newStatus}.");
    }

    public function destroy(User $operator)
    {
        $user = Auth::user();
        if (!$user->isMainExporter() || $operator->parent_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $name = $operator->name;
        $operator->delete();

        return back()->with('success', "Operator \"{$name}\" removed successfully.");
    }
}
