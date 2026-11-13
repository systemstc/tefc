<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextileCategory;
use App\Models\TextileSubcategory;
use App\Models\TextileProduct;
use App\Models\FibreMaster;
use App\Models\YarnMaster;
use App\Models\FabricMaster;
use App\Models\ProcessMaster;

class TextileClassificationController extends Controller
{
    /**
     * Search classifications by keyword, product name, or synonym.
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = TextileProduct::with(['subcategory.category'])
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('synonyms', 'like', "%{$q}%")
                      ->orWhere('code', 'like', "%{$q}%")
                      ->orWhere('default_hs_code', 'like', "%{$q}%")
                      ->orWhereHas('subcategory', function ($sub) use ($q) {
                          $sub->where('name', 'like', "%{$q}%")
                              ->orWhereHas('category', function ($cat) use ($q) {
                                  $cat->where('name', 'like', "%{$q}%");
                              });
                      });
            })
            ->limit(25)
            ->get()
            ->map(function ($item) {
                $category = $item->subcategory?->category;
                $subcategory = $item->subcategory;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'description' => $item->description,
                    'subcategory_id' => $subcategory?->id,
                    'subcategory_name' => $subcategory?->name,
                    'category_id' => $category?->id,
                    'category_code' => $category?->code,
                    'category_name' => $category?->name,
                    'default_hs_code' => $item->default_hs_code,
                    'breadcrumb' => ($category ? $category->name . ' › ' : '') .
                                    ($subcategory ? $subcategory->name . ' › ' : '') .
                                    $item->name,
                ];
            });

        return response()->json($results);
    }

    /**
     * Get the full categories & subcategories tree for browsing.
     */
    public function tree()
    {
        $categories = TextileCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }, 'subcategories.products' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

        return response()->json($categories);
    }

    /**
     * Get the subcategories for a category.
     */
    public function getSubcategories(Request $request, ?TextileCategory $category = null)
    {
        $catId = $category?->id ?? $request->input('category_id');
        $subcategories = TextileSubcategory::where('category_id', $catId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($subcategories);
    }

    /**
     * Get products under a subcategory.
     */
    public function getProducts(Request $request, ?TextileSubcategory $subcategory = null)
    {
        $subId = $subcategory?->id ?? $request->input('subcategory_id');
        $products = TextileProduct::where('subcategory_id', $subId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($products);
    }

    /**
     * Get technical attribute rules and master lists for a given category.
     */
    public function getCategoryAttributes(Request $request, ?TextileCategory $category = null)
    {
        $catId = $category?->id ?? $request->input('category_id');
        $cat = $category ?? TextileCategory::find($catId);
        if (!$cat) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $rules = $cat->attributeRules()->get()->keyBy('attribute_type');

        $fibres = FibreMaster::where('is_active', true)->orderBy('sort_order')->get();
        $yarns = YarnMaster::where('is_active', true)->orderBy('sort_order')->get();
        $fabrics = FabricMaster::where('is_active', true)->orderBy('sort_order')->get();
        $processes = ProcessMaster::where('is_active', true)->orderBy('sort_order')->get();

        return response()->json([
            'category_id' => $cat->id,
            'category_code' => $cat->code,
            'category_name' => $cat->name,
            'rules' => $rules,
            'fibres' => $fibres,
            'yarns' => $yarns,
            'fabrics' => $fabrics,
            'processes' => $processes,
        ]);
    }
}
