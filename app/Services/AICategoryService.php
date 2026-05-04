<?php
// app/Services/AICategoryService.php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AICategoryService
{
    // Modèles à essayer dans l'ordre (fallback automatique)
    private array $models = [
        'gemini-3-flash-preview',
        'gemini-2.5-flash',
        'gemini-2.0-flash',
        'gemini-2.0-flash-lite',
    ];

    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.gemini.api_key');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
    }

    /**
     * Suggère une catégorie pour un produit donné, en créant une nouvelle si nécessaire.
     *
     * @param  string   $productName
     * @param  string   $description
     * @param  int|null $userId
     * @return array    Structure JSON demandée + category_id
     */
    public function suggestCategory(string $productName, string $description = '', int $userId = null): array
    {
        // 1. Récupérer les catégories existantes
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $categoryList = $categories->pluck('name')->implode(', ');

        // 2. Prompt détaillé selon les règles de l'utilisateur
        $prompt = "Tu es un agent intelligent de gestion de catalogue produits.\n\n"
            . "MISSION:\n"
            . "Analyse le nom et la description du produit et identifie la catégorie la plus appropriée.\n\n"
            . "RÈGLES:\n"
            . "1. Liste des catégories existantes: [{$categoryList}]\n"
            . "2. Si une catégorie existante correspond bien -> assigne-la.\n"
            . "3. Si aucune ne correspond -> crée une nouvelle catégorie (nom clair, concis, singulier, Majuscule initiale, en Français).\n"
            . "4. Ne jamais laisser un produit sans catégorie.\n"
            . "5. Évite les doublons sémantiques.\n"
            . "6. En cas de doute, choisis la plus spécifique.\n\n"
            . "PRODUIT:\n"
            . "Nom: \"{$productName}\"\n"
            . "Description: \"{$description}\"\n\n"
            . "RETOURNE UNIQUEMENT CE JSON:\n"
            . "{\n"
            . "  \"product_name\": \"{$productName}\",\n"
            . "  \"assigned_category\": \"<nom de la catégorie>\",\n"
            . "  \"is_new_category\": true | false,\n"
            . "  \"confidence\": \"high\" | \"medium\" | \"low\",\n"
            . "  \"reasoning\": \"<explication courte en français>\"\n"
            . "}";

        foreach ($this->models as $model) {
            try {
                $result = $this->callGemini($model, $prompt);

                if ($result !== null && isset($result['assigned_category'])) {
                    // S'assurer que le nom est une chaîne
                    $categoryName = is_array($result['assigned_category']) 
                        ? ($result['assigned_category']['name'] ?? 'Inconnu') 
                        : (string)$result['assigned_category'];
                    
                    $isNew = filter_var($result['is_new_category'] ?? false, FILTER_VALIDATE_BOOLEAN);
                    $categoryId = null;

                    // 3. Gérer la catégorie (Existante ou Nouvelle)
                    $existingCategory = $categories->first(function($c) use ($categoryName) {
                        return strtolower(trim($c->name)) === strtolower(trim($categoryName));
                    });

                    if ($existingCategory) {
                        $categoryId = $existingCategory->id;
                        $categoryName = $existingCategory->name;
                        $isNew = false;
                    }

                    if ($isNew) {
                        try {
                            // Création de la nouvelle catégorie
                            $category = Category::create([
                                'name'    => ucfirst(strtolower(trim($categoryName))),
                                'user_id' => $userId ?? auth()->id(),
                                'description' => 'Créé automatiquement par IA pour: ' . $productName
                            ]);
                            $categoryId = $category->id;
                            $categoryName = $category->name;
                        } catch (\Exception $dbEx) {
                            Log::error("Erreur création catégorie IA: " . $dbEx->getMessage());
                            // Fallback: essayer de trouver si elle a été créée entre temps ou utiliser une existante
                            $fallbackCat = Category::where('name', trim($categoryName))->first();
                            if ($fallbackCat) {
                                $categoryId = $fallbackCat->id;
                                $isNew = false;
                            } else {
                                return $this->errorResponse('Erreur lors de la création de la catégorie suggérée.');
                            }
                        }
                    }

                    return [
                        'success'           => true,
                        'product_name'      => $productName,
                        'assigned_category' => $categoryName,
                        'category_id'       => $categoryId,
                        'is_new_category'   => $isNew,
                        'confidence'        => $result['confidence'] ?? 'medium',
                        'reasoning'         => $result['reasoning'] ?? 'Analyse IA.',
                        'category_name'     => $categoryName, 
                        'reason'            => $result['reasoning'] ?? 'Analyse IA.',
                    ];
                }
            } catch (\Exception $loopEx) {
                Log::error("Erreur boucle IA [{$model}]: " . $loopEx->getMessage());
                continue;
            }
        }

        return $this->errorResponse('Impossible de contacter l\'IA pour le moment.');
    }

    /**
     * Appelle l'API Gemini avec un modèle donné.
     * Retourne le tableau parsé ou null si erreur.
     */
    private function callGemini(string $model, string $prompt): ?array
    {
        try {
            $url = "{$this->baseUrl}/{$model}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(20)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [['text' => $prompt]]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.1,
                        'maxOutputTokens' => 1000,
                    ],
                ]);

            Log::debug("Gemini [{$model}] status: " . $response->status());

            if (!$response->successful()) {
                Log::warning("Gemini [{$model}] HTTP {$response->status()}: " . substr($response->body(), 0, 200));
                return null;
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$text) {
                Log::warning("Gemini [{$model}] empty text response");
                return null;
            }

            // Nettoyer le JSON (supprimer les backticks markdown si présents)
            $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
            $text = preg_replace('/\s*```$/', '', $text);
            $text = trim($text);

            // Extraire le JSON s'il y a du texte autour
            if (preg_match('/\{[^}]+\}/s', $text, $matches)) {
                $text = $matches[0];
            }

            $result = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning("Gemini [{$model}] invalid JSON: {$text}");
                return null;
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("Gemini [{$model}] exception: " . $e->getMessage());
            return null;
        }
    }

    private function errorResponse(string $message): array
    {
        return ['success' => false, 'message' => $message];
    }
}
