<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QwenVlService
{
    protected string $provider;

    // Groq Cloud Configuration
    protected string $groqApiKey;
    protected string $groqModel;
    protected string $groqEndpoint;
    protected int $groqTimeout;
    protected float $groqTemperature;

    // Local Ollama Configuration (Fallback)
    protected string $ollamaUrl;
    protected string $ollamaModel;
    protected int $ollamaTimeout;
    protected float $ollamaTemperature;

    public function __construct()
    {
        $this->provider = config('ai.provider', env('AI_PROVIDER', 'groq'));

        // Groq settings (Default: qwen/qwen3.8-27b)
        $this->groqApiKey = config('ai.groq.api_key', env('GROQ_API_KEY', ''));
        $this->groqModel = config('ai.groq.model', env('GROQ_MODEL', 'qwen/qwen3.8-27b'));
        $this->groqEndpoint = config('ai.groq.endpoint', env('GROQ_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions'));
        $this->groqTimeout = (int) config('ai.groq.timeout', env('GROQ_TIMEOUT', 30));
        $this->groqTemperature = (float) config('ai.groq.temperature', env('GROQ_TEMPERATURE', 0.1));

        // Ollama settings (Local Fallback)
        $this->ollamaUrl = rtrim(config('ai.ollama.url', config('ai.ollama_url', env('OLLAMA_URL', 'http://localhost:11434'))), '/');
        $this->ollamaModel = config('ai.ollama.vl_model', config('ai.vl_model', env('OLLAMA_VL_MODEL', 'qwen3-vl:2b-instruct')));
        $this->ollamaTimeout = (int) config('ai.ollama.timeout', config('ai.timeout', env('OLLAMA_TIMEOUT', 180)));
        $this->ollamaTemperature = (float) config('ai.ollama.temperature', config('ai.temperature', env('OLLAMA_TEMPERATURE', 0.1)));
    }

    /**
     * Memeriksa status konektivitas AI Service (Groq Cloud API & Ollama Local).
     */
    public function checkHealth(): array
    {
        if ($this->provider === 'groq') {
            return $this->checkGroqHealth();
        }

        return $this->checkOllamaHealth();
    }

    /**
     * Cek status Groq Cloud API.
     */
    protected function checkGroqHealth(): array
    {
        if (empty($this->groqApiKey) || str_starts_with($this->groqApiKey, 'gsk_paste')) {
            return [
                'online' => false,
                'provider' => 'groq',
                'model' => $this->groqModel,
                'message' => 'GROQ_API_KEY belum dikonfigurasi di file .env.',
            ];
        }

        try {
            $response = Http::withToken($this->groqApiKey)
                ->timeout(10)
                ->get('https://api.groq.com/openai/v1/models');

            if (! $response->successful()) {
                return [
                    'online' => false,
                    'provider' => 'groq',
                    'model' => $this->groqModel,
                    'message' => 'Groq API merespons dengan kesalahan (Status: '.$response->status().')',
                ];
            }

            $models = collect($response->json('data', []))->pluck('id')->toArray();
            $hasModel = in_array($this->groqModel, $models);

            return [
                'online' => true,
                'provider' => 'groq',
                'has_target_model' => $hasModel,
                'model' => $this->groqModel,
                'message' => $hasModel
                    ? "Groq Cloud API online & model {$this->groqModel} siap digunakan (Ultra Fast)."
                    : "Groq Cloud API online namun model {$this->groqModel} tidak ditemukan di daftar model aktif.",
            ];
        } catch (\Throwable $e) {
            return [
                'online' => false,
                'provider' => 'groq',
                'model' => $this->groqModel,
                'message' => 'Gagal menghubungi Groq Cloud API: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Cek status Ollama server lokal.
     */
    protected function checkOllamaHealth(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->ollamaUrl}/api/tags");

            if (! $response->successful()) {
                return [
                    'online' => false,
                    'provider' => 'ollama',
                    'message' => 'Ollama server tidak merespons (Status: '.$response->status().')',
                    'models' => [],
                ];
            }

            $models = collect($response->json('models', []))->pluck('name')->toArray();
            $hasModel = in_array($this->ollamaModel, $models);

            return [
                'online' => true,
                'provider' => 'ollama',
                'has_target_model' => $hasModel,
                'model' => $this->ollamaModel,
                'available_models' => $models,
                'message' => $hasModel
                    ? "Ollama online & model {$this->ollamaModel} siap digunakan."
                    : "Ollama online namun model {$this->ollamaModel} belum ditemukan di daftar.",
            ];
        } catch (\Throwable $e) {
            return [
                'online' => false,
                'provider' => 'ollama',
                'message' => 'Gagal terhubung ke Ollama di '.$this->ollamaUrl.': '.$e->getMessage(),
                'models' => [],
            ];
        }
    }

    /**
     * Menganalisis gambar produk secara visual untuk merekomendasikan Kategori, Supplier,
     * dan menghasilkan deskripsi ringkas produk menggunakan Qwen 3.8 27B dari Groq.
     *
     * @param  string  $rawBase64  Base64 murni dari file gambar tanpa prefix data:image/...
     * @param  iterable  $categories  Daftar objek/koleksi Kategori dari database
     * @param  iterable  $suppliers  Daftar objek/koleksi Supplier dari database
     */
    public function analyzeProductImage(string $rawBase64, iterable $categories = [], iterable $suppliers = []): array
    {
        $categoryCollection = collect($categories);
        $supplierCollection = collect($suppliers);

        $categoryLines = $categoryCollection->map(function ($cat) {
            $id = is_object($cat) ? $cat->id : ($cat['id'] ?? null);
            $name = is_object($cat) ? $cat->name : ($cat['name'] ?? null);

            return "- ID: {$id} | Nama: {$name}";
        })->implode("\n");

        $supplierLines = $supplierCollection->map(function ($sup) {
            $id = is_object($sup) ? $sup->id : ($sup['id'] ?? null);
            $name = is_object($sup) ? $sup->name : ($sup['name'] ?? null);

            return "- ID: {$id} | Nama: {$name}";
        })->implode("\n");

        $catText = ! empty($categoryLines) ? $categoryLines : '(Belum ada kategori terdaftar)';
        $supText = ! empty($supplierLines) ? $supplierLines : '(Belum ada supplier terdaftar)';

        $prompt = <<<EOT
Analisis foto produk ini untuk kebutuhan inventaris katalog gudang toko.
Tugas Anda:
1. Pilih SATU ID Kategori yang paling sesuai dari daftar berikut (atau null jika tidak ada yang cocok):
{$catText}

2. Pilih SATU ID Supplier yang paling relevan dari daftar berikut (atau null jika tidak ada yang cocok):
{$supText}

3. Tuliskan deskripsi ringkas produk (1-2 kalimat dalam Bahasa Indonesia) yang menyebutkan jenis/nama barang dan cirinya.

Jawab HANYA dalam format JSON valid tanpa teks pengantar:
{
  "category_id": 1,
  "supplier_id": 1,
  "suggested_description": "Deskripsi produk di sini..."
}
EOT;

        // 1. Eksekusi menggunakan Groq API jika menjadi provider utama
        if ($this->provider === 'groq' && ! empty($this->groqApiKey)) {
            $result = $this->callGroqVision($prompt, $rawBase64);

            if ($result['success']) {
                return $this->formatRecommendationResult($result['raw_json'], $categoryCollection, $supplierCollection, 'groq');
            }

            Log::warning('QwenVlService: Groq Vision gagal, mencoba fallback ke Ollama lokal...', [
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }

        // 2. Fallback / Eksekusi Ollama Lokal
        $ollamaResult = $this->callOllamaVision($prompt, $rawBase64);
        if ($ollamaResult['success']) {
            return $this->formatRecommendationResult($ollamaResult['raw_json'], $categoryCollection, $supplierCollection, 'ollama');
        }

        return [
            'success' => false,
            'message' => $ollamaResult['message'] ?? 'Gagal memproses gambar produk dengan model AI.',
        ];
    }

    /**
     * Memanggil Groq Cloud Chat Completion API dengan multimodal input (Vision).
     */
    protected function callGroqVision(string $prompt, string $rawBase64): array
    {
        try {
            $payload = [
                'model' => $this->groqModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:image/jpeg;base64,'.$rawBase64,
                                ],
                            ],
                        ],
                    ],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => $this->groqTemperature,
                'max_tokens' => 350,
            ];

            $response = Http::withToken($this->groqApiKey)
                ->timeout($this->groqTimeout)
                ->post($this->groqEndpoint, $payload);

            if (! $response->successful()) {
                Log::error('QwenVlService: Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Groq API error (Kode: '.$response->status().'): '.($response->json('error.message') ?? 'Unknown error'),
                ];
            }

            $content = trim((string) $response->json('choices.0.message.content'));

            return [
                'success' => true,
                'raw_json' => $content,
            ];
        } catch (\Throwable $e) {
            Log::error('QwenVlService: Groq Vision Exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Groq Cloud Exception: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Memanggil Ollama Local API (Fallback).
     */
    protected function callOllamaVision(string $prompt, string $rawBase64): array
    {
        try {
            $payload = [
                'model' => $this->ollamaModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                        'images' => [$rawBase64],
                    ],
                ],
                'format' => 'json',
                'stream' => false,
                'options' => [
                    'temperature' => $this->ollamaTemperature,
                    'num_predict' => 160,
                ],
            ];

            $response = Http::timeout($this->ollamaTimeout)
                ->post("{$this->ollamaUrl}/api/chat", $payload);

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Ollama API error: '.$response->status(),
                ];
            }

            $content = trim((string) $response->json('message.content'));

            return [
                'success' => true,
                'raw_json' => $content,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Ollama error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Parse and validate recommendations against master database (Anti-Hallucination).
     */
    protected function formatRecommendationResult(string $rawContent, $categoryCollection, $supplierCollection, string $usedProvider): array
    {
        $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $rawContent);
        $parsed = json_decode($cleanJson, true);

        $description = '';
        $categoryId = null;
        $supplierId = null;

        if (is_array($parsed)) {
            if (! empty($parsed['suggested_description'])) {
                $description = trim($parsed['suggested_description']);
            }

            if (isset($parsed['category_id']) && is_numeric($parsed['category_id'])) {
                $categoryId = (int) $parsed['category_id'];
            }

            if (isset($parsed['supplier_id']) && is_numeric($parsed['supplier_id'])) {
                $supplierId = (int) $parsed['supplier_id'];
            }
        } else {
            $description = $cleanJson;
        }

        // Validasi anti-halusinasi: Pastikan ID benar-benar terdaftar di database
        $categoryMap = $categoryCollection->keyBy(fn ($item) => is_object($item) ? $item->id : $item['id']);
        $supplierMap = $supplierCollection->keyBy(fn ($item) => is_object($item) ? $item->id : $item['id']);

        $matchedCategory = ($categoryId && $categoryMap->has($categoryId)) ? $categoryMap->get($categoryId) : null;
        $matchedSupplier = ($supplierId && $supplierMap->has($supplierId)) ? $supplierMap->get($supplierId) : null;

        $matchedCategoryId = $matchedCategory ? (is_object($matchedCategory) ? $matchedCategory->id : $matchedCategory['id']) : null;
        $matchedCategoryName = $matchedCategory ? (is_object($matchedCategory) ? $matchedCategory->name : $matchedCategory['name']) : null;

        $matchedSupplierId = $matchedSupplier ? (is_object($matchedSupplier) ? $matchedSupplier->id : $matchedSupplier['id']) : null;
        $matchedSupplierName = $matchedSupplier ? (is_object($matchedSupplier) ? $matchedSupplier->name : $matchedSupplier['name']) : null;

        return [
            'success' => true,
            'provider' => $usedProvider,
            'model' => $usedProvider === 'groq' ? $this->groqModel : $this->ollamaModel,
            'data' => [
                'category_id' => $matchedCategoryId,
                'category_name' => $matchedCategoryName,
                'supplier_id' => $matchedSupplierId,
                'supplier_name' => $matchedSupplierName,
                'suggested_description' => $description,
            ],
        ];
    }

    /**
     * Menghasilkan deskripsi ringkas produk dari foto visual dengan performa tinggi.
     */
    public function generateProductDescription(string $rawBase64): array
    {
        $prompt = "Tuliskan deskripsi ringkas produk pada foto ini (1-2 kalimat dalam Bahasa Indonesia) untuk katalog inventaris gudang toko. Sebutkan nama/merek barang dan kegunaan atau cirinya.\nJawab HANYA dalam format JSON valid:\n{\"suggested_description\": \"...\"}";

        if ($this->provider === 'groq' && ! empty($this->groqApiKey)) {
            $result = $this->callGroqVision($prompt, $rawBase64);
            if ($result['success']) {
                $parsed = json_decode($result['raw_json'], true);
                $desc = is_array($parsed) && ! empty($parsed['suggested_description'])
                    ? trim($parsed['suggested_description'])
                    : trim($result['raw_json']);

                return [
                    'success' => true,
                    'provider' => 'groq',
                    'model' => $this->groqModel,
                    'data' => [
                        'suggested_description' => $desc,
                    ],
                ];
            }
        }

        // Fallback to Ollama
        $ollamaResult = $this->callOllamaVision($prompt, $rawBase64);
        if ($ollamaResult['success']) {
            $parsed = json_decode($ollamaResult['raw_json'], true);
            $desc = is_array($parsed) && ! empty($parsed['suggested_description'])
                ? trim($parsed['suggested_description'])
                : trim($ollamaResult['raw_json']);

            return [
                'success' => true,
                'provider' => 'ollama',
                'model' => $this->ollamaModel,
                'data' => [
                    'suggested_description' => $desc,
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal menghasilkan deskripsi produk.',
        ];
    }

    /**
     * Helper untuk percakapan teks / Asisten AI
     */
    public function chat(array $messages, ?string $systemPrompt = null): array
    {
        $allMessages = [];
        if ($systemPrompt) {
            $allMessages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        foreach ($messages as $msg) {
            $allMessages[] = $msg;
        }

        if ($this->provider === 'groq' && ! empty($this->groqApiKey)) {
            try {
                $response = Http::withToken($this->groqApiKey)
                    ->timeout($this->groqTimeout)
                    ->post($this->groqEndpoint, [
                        'model' => $this->groqModel,
                        'messages' => $allMessages,
                        'temperature' => $this->groqTemperature,
                        'max_tokens' => 500,
                    ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'provider' => 'groq',
                        'content' => $response->json('choices.0.message.content'),
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Groq Chat failed, falling back to Ollama: '.$e->getMessage());
            }
        }

        // Ollama fallback
        try {
            $response = Http::timeout($this->ollamaTimeout)
                ->post("{$this->ollamaUrl}/api/chat", [
                    'model' => $this->ollamaModel,
                    'messages' => $allMessages,
                    'stream' => false,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'provider' => 'ollama',
                    'content' => $response->json('message.content'),
                ];
            }

            return [
                'success' => false,
                'message' => 'Ollama API error: '.$response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
