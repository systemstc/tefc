<?php

namespace App\Http\Controllers;

use App\Services\BhashiniService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BhashiniController extends Controller
{
    protected BhashiniService $bhashini;

    public function __construct(BhashiniService $bhashini)
    {
        $this->bhashini = $bhashini;
    }

    /**
     * Check Bhashini service configuration status and list of supported languages.
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'configured' => $this->bhashini->isConfigured(),
            'languages' => $this->bhashini->getLanguages(),
            'info' => 'Digital India Bhashini (National Language Translation Mission - MeitY)',
        ]);
    }

    /**
     * Translate text or array of texts.
     */
    public function translate(Request $request): JsonResponse
    {
        $request->validate([
            'target_lang' => 'required|string|max:10',
            'source_lang' => 'nullable|string|max:10',
            'text' => 'required',
        ]);

        $text = $request->input('text');
        $targetLang = $request->input('target_lang');
        $sourceLang = $request->input('source_lang', 'en');

        $result = $this->bhashini->translate($text, $targetLang, $sourceLang);

        return response()->json($result);
    }
}
