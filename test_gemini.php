<?php
// Script de test de l'API Gemini - à exécuter avec: php test_gemini.php

$key    = 'AIzaSyCioDrGKYU98My_djQOpFRhIVoiZhQv0CQ';
$models = ['gemini-2.0-flash', 'gemini-1.5-flash', 'gemini-1.5-flash-latest', 'gemini-pro'];

echo "=== Test API Google Gemini ===\n\n";

foreach ($models as $model) {
    $url  = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";
    $data = json_encode([
        'contents' => [['parts' => [['text' => 'Réponds uniquement avec le mot: OK']]]],
        'generationConfig' => ['maxOutputTokens' => 10],
    ]);

    $ctx = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => "Content-Type: application/json\r\n",
            'content'       => $data,
            'timeout'       => 12,
            'ignore_errors' => true,
        ]
    ]);

    $res  = @file_get_contents($url, false, $ctx);
    $code = $http_response_header[0] ?? 'Pas de réponse';

    if (strpos($code, '200') !== false) {
        $json   = json_decode($res, true);
        $answer = $json['candidates'][0]['content']['parts'][0]['text'] ?? 'vide';
        echo "[OK] {$model} => HTTP 200 | Réponse: " . trim($answer) . "\n";
    } else {
        $err = json_decode($res, true);
        $msg = $err['error']['message'] ?? substr($res, 0, 80);
        echo "[KO] {$model} => {$code} | {$msg}\n";
    }
}

echo "\nTest terminé.\n";
