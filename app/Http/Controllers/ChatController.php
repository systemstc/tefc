<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string|max:100',
        ]);

        $userMessage = $request->input('message');
        $sessionId = $request->input('session_id');
        
        // Read provider from the .env configuration, defaulting to 'gemini'
        $provider = env('CHAT_PROVIDER', 'gemini');

        // Save user message to database
        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
            'sender' => 'user',
            'message' => $userMessage,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $apiKey = ($provider === 'gemini') ? env('GEMINI_API_KEY') : env('OPENAI_API_KEY');

        if (empty($apiKey)) {
            $providerName = ($provider === 'gemini') ? 'Gemini' : 'OpenAI';
            $envKeyName = ($provider === 'gemini') ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            $offlineMsg = "The Chatbot is currently offline because the {$providerName} API Key is missing. Please configure the `{$envKeyName}` in the server `.env` file to enable the assistant.";
            
            // Save offline/warning message to database
            ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'sender' => 'assistant',
                'message' => $offlineMsg,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $offlineMsg,
            ]);
        }

        // Search matching approved products in the database to build context
        $keywords = explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $userMessage)));
        $productQuery = Product::where('status', 'Approved');
        
        $hasKeyword = false;
        $productQuery->where(function($q) use ($keywords, &$hasKeyword) {
            foreach ($keywords as $word) {
                if (strlen($word) > 3) {
                    $q->orWhere('name', 'LIKE', "%{$word}%")
                      ->orWhere('cluster', 'LIKE', "%{$word}%")
                      ->orWhere('hs_code', 'LIKE', "%{$word}%");
                    $hasKeyword = true;
                }
            }
        });

        $productsContext = '';
        if ($hasKeyword) {
            $products = $productQuery->limit(5)->get();
            if ($products->count() > 0) {
                $productsContext = "\nHere are some matching approved products from our portal catalogue:\n";
                foreach ($products as $p) {
                    $cats = is_array($p->category) ? $p->category : json_decode($p->category, true) ?? [];
                    $categoryStr = is_array($cats) ? implode(', ', $cats) : $p->category;
                    $productsContext .= "- Name: {$p->name}, Category: {$categoryStr}, Cluster: {$p->cluster}, HS Code: {$p->hs_code}, Color: {$p->color}, Material: {$p->material_composition}, Certifications: {$p->certifications}. View link: /catalogue/{$p->id}\n";
                }
            }
        }

        // System instructions detailing TEFC registration, benefits, links etc.
        $systemInstructions = "You are the TEFC AI Export Assistant, the official digital assistant of the Textile Export Facilitation Centre (TEFC), Ministry of Textiles, Government of India.
Your goal is to provide accurate, helpful, and polite information to users regarding textile exports, registration processes, government schemes, brochures/resources, and approved product details.

TEFC Registration Information:
- Sellers (Exporters): Can register by entering their IEC (Import Export Code) and GST details. They can create/edit profiles and add products (specifying name, category, cluster, HS code, width/size, pattern, color, material, certifications, etc.). Link: /register?role=seller
- Buyers: International buyers can register to browse products, view details, search the directory, and submit inquiries. Link: /register?role=buyer

Active Government Schemes:
- RoDTEP: Remission of Duties & Taxes on Exported Products (0.5% - 4.3% rebate on FOB value). Link: /p/fta-benefits#comparison
- PLI Scheme: Production Linked Incentive for Textiles (up to ₹10 Cr annual incentives on MMF and technical textiles). Link: /p/fta-benefits#agreements
- TUFS: Technology Upgradation Fund Scheme (15% capital subsidy on new textile machinery). Link: /p/fta-benefits
- RoSCTL: Rebate of State and Central Taxes & Levies (0.5% - 8.2% rebate for apparel & madeups). Link: /p/fta-benefits
- PMS/DP: PM Development Demonstration Programme (15%-35% margin subsidy for small/handloom manufacturing units).
- SFURTI: Scheme of Fund for Regeneration of Traditional Industries (₹2-5 Cr grant for clusters capacity).

Active Free Trade Agreements (FTAs):
- Advise users to visit the '/p/fta-benefits' page to inspect active agreements (such as India-Australia ECTA, India-UAE CEPA, SAFTA, etc.) and check duty rates.

Brochures & Downloads:
- Exporters can download manuals, trade reports, and scheme guidelines from the '/resources' page.

Online Services:
- Exporters can apply for Certificates of Origin (CoO) or Import Export Codes (IEC) and check links on the '/p/online-services' page.
- Direct links: CoO application guide: '/p/coo', IEC guide: '/p/iec', Letter of Credit guide: '/p/letter-of-credit', HS Code search guide: '/p/hs-code', ECGC Services guide: '/p/ecgc'.

Rules for your responses:
- Keep answers concise, formal, and polite.
- Always provide absolute path links (e.g. '/p/fta-benefits', '/register?role=seller', '/catalogue', '/p/online-services', '/resources', '/p/coo', '/p/iec', '/p/help-support') and encourage the user to click them directly when relevant.
- Do not guess or invent URL paths. Use only the paths listed above.
" . $productsContext;

        try {
            if ($provider === 'gemini') {
                // Call Google Gemini API
                $response = Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $userMessage]
                            ]
                        ]
                    ],
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstructions]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $reply = $response->json('candidates.0.content.parts.0.text') ?? 'I apologize, I could not process that request.';

                    // Save assistant message to database
                    ChatMessage::create([
                        'session_id' => $sessionId,
                        'user_id' => auth()->id(),
                        'sender' => 'assistant',
                        'message' => $reply,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => $reply,
                    ]);
                }

                $errorMsg = $response->json('error.message') ?? 'Gemini API request failed.';
            } else {
                // Call OpenAI API
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemInstructions],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 800,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['choices'][0]['message']['content'] ?? 'I apologize, I could not process that request.';

                    // Save assistant message to database
                    ChatMessage::create([
                        'session_id' => $sessionId,
                        'user_id' => auth()->id(),
                        'sender' => 'assistant',
                        'message' => $reply,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => $reply,
                    ]);
                }

                $errorMsg = $response->json('error.message') ?? 'OpenAI API request failed.';
            }

            // Save error assistant response to database
            ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'sender' => 'assistant',
                'message' => 'Error: ' . $errorMsg,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error from Assistant: ' . $errorMsg,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
}
