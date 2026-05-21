<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AiService
{
    /**
     * Generate dynamic AI insights based on analytics data.
     *
     * @param array $analyticsData
     * @return array
     */
    public function generateInsights(array $analyticsData): array
    {
        $provider = Setting::get('ai_provider') ?: env('AI_DEFAULT_PROVIDER', 'openrouter');
        
        if ($provider === 'disabled') {
            return [];
        }

        $model = Setting::get('ai_model') ?: env('AI_DEFAULT_MODEL', 'qwen/qwen-2.5-7b-instruct:free');
        
        // 1. Resolve API Key (Database or Env fallback)
        $encryptedKey = Setting::get('ai_api_key');
        $apiKey = null;

        if ($encryptedKey) {
            try {
                $apiKey = Crypt::decryptString($encryptedKey);
            } catch (\Exception $e) {
                Log::warning('AiService: Failed to decrypt API Key from database settings: ' . $e->getMessage());
            }
        }

        if (empty($apiKey)) {
            if ($provider === 'openrouter') {
                $apiKey = env('OPENROUTER_API_KEY');
            } elseif ($provider === 'deepseek') {
                $apiKey = env('DEEPSEEK_API_KEY');
            } elseif ($provider === 'gemini') {
                $apiKey = env('GEMINI_API_KEY');
            }
        }

        if (empty($apiKey)) {
            Log::info("AiService: API Key is not configured for provider [{$provider}]. Falling back to static insights.");
            return [];
        }

        // 2. Build Prompt
        $prompt = $this->buildPrompt($analyticsData);

        try {
            $textResponse = '';

            // 3. Make API Request
            if ($provider === 'openrouter') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => url('/'),
                    'X-Title' => 'AutoShow Pro',
                ])->timeout(7)->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model ?: 'qwen/qwen-2.5-7b-instruct:free',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7
                ]);

                if ($response->successful()) {
                    $textResponse = $response->json('choices.0.message.content') ?? '';
                } else {
                    Log::error("AiService: OpenRouter request failed. Status: {$response->status()}, Body: {$response->body()}");
                }

            } elseif ($provider === 'deepseek') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ])->timeout(7)->post('https://api.deepseek.com/chat/completions', [
                    'model' => $model ?: 'deepseek-chat',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7
                ]);

                if ($response->successful()) {
                    $textResponse = $response->json('choices.0.message.content') ?? '';
                } else {
                    Log::error("AiService: DeepSeek request failed. Status: {$response->status()}, Body: {$response->body()}");
                }

            } elseif ($provider === 'gemini') {
                $modelName = $model ?: 'gemini-1.5-flash';
                $response = Http::timeout(7)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7
                    ]
                ]);

                if ($response->successful()) {
                    $textResponse = $response->json('candidates.0.content.parts.0.text') ?? '';
                } else {
                    Log::error("AiService: Gemini request failed. Status: {$response->status()}, Body: {$response->body()}");
                }
            }

            if (empty($textResponse)) {
                return [];
            }

            // 4. Parse JSON Response
            return $this->parseJsonResponse($textResponse);

        } catch (\Exception $e) {
            Log::error('AiService: Error occurred while fetching AI insights: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Build the structured prompt for the AI model.
     *
     * @param array $data
     * @return string
     */
    private function buildPrompt(array $data): string
    {
        $visits = $data['total_visits'] ?? 0;
        $bookings = $data['total_bookings'] ?? 0;
        $convRate = $data['conversion_rate'] ?? 0;
        $peakHour = $data['peak_hour'] ?? 'Tidak ada data';
        $trendingCar = $data['trending_car'] ?? 'Tidak ada data';
        $daysSinceGallery = $data['days_since_gallery'] ?? 'Tidak ada data';

        return "Anda adalah AutoShow AI, asisten analis penjualan mobil profesional yang memberikan saran bisnis pintar, ringkas, dan langsung dapat dieksekusi oleh sales/administrator dealer.

Berdasarkan data analitik dealer berikut:
- Total Kunjungan Website (30 hari terakhir): {$visits}
- Total Pengajuan Test Drive (30 hari terakhir): {$bookings}
- Rasio Konversi Kunjungan ke Booking: {$convRate}%
- Jam Kunjungan Terramai: {$peakHour} WIB
- Mobil Paling Tren (dilihat paling banyak minggu ini): {$trendingCar}
- Terakhir Update Galeri Foto: {$daysSinceGallery} hari yang lalu

Berikan 3 atau 4 saran strategi penjualan/pemasaran taktis dalam Bahasa Indonesia.
Anda WAJIB mengembalikan respon dalam format JSON ARRAY murni (tanpa markdown block, tanpa penjelasan tambahan) dengan struktur objek sebagai berikut:
[
  {
    \"title\": \"Judul Insight Singkat\",
    \"text\": \"Saran detail, ringkas, dan taktis (maksimal 2 kalimat).\",
    \"icon\": \"heroicon-o-nama-icon-heroicons-v3\", (PILIH ICON YANG SESUAI DARI: heroicon-o-light-bulb, heroicon-o-presentation-chart-line, heroicon-o-camera, heroicon-o-megaphone, heroicon-o-fire, heroicon-o-arrow-trending-up)
    \"color\": \"warna-badge\" (PILIH WARNA SESUAI DARI: success, warning, danger, info, primary, gray)
  }
]
Format keluaran Anda harus valid JSON yang dapat langsung diparsing oleh PHP json_decode.";
    }

    /**
     * Parse the raw AI text response into a structured array.
     *
     * @param string $text
     * @return array
     */
    private function parseJsonResponse(string $text): array
    {
        // Strip markdown codeblock wrappers if they are returned
        $cleaned = trim($text);
        if (preg_match('/\[\s*\{.*\}\s*\]/s', $cleaned, $matches)) {
            $cleaned = $matches[0];
        }

        $decoded = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            Log::warning('AiService: Failed to parse JSON response from AI. Raw response: ' . $text);
            return [];
        }

        // Validate the structure of each insight
        $validInsights = [];
        foreach ($decoded as $item) {
            if (isset($item['title'], $item['text'])) {
                $validInsights[] = [
                    'title' => (string) $item['title'],
                    'text' => (string) $item['text'],
                    'icon' => (string) ($item['icon'] ?? 'heroicon-o-light-bulb'),
                    'color' => (string) ($item['color'] ?? 'primary'),
                ];
            }
        }

        return $validInsights;
    }
}
