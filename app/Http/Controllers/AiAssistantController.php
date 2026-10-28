<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use App\Services\Ai\QwenVlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    protected QwenVlService $qwenService;

    public function __construct(QwenVlService $qwenService)
    {
        $this->qwenService = $qwenService;
    }

    /**
     * Cek status konektivitas Ollama & ketersediaan model Qwen-VL.
     */
    public function healthCheck(): JsonResponse
    {
        $health = $this->qwenService->checkHealth();

        return response()->json($health, $health['online'] ? 200 : 503);
    }

    /**
     * Menganalisis file foto produk untuk menghasilkan deskripsi produk secara cepat.
     */
    public function analyzeProductImage(Request $request): JsonResponse
    {
        // Cegah timeout PHP saat inferensi model
        @ini_set('max_execution_time', 180);
        @set_time_limit(180);

        $request->validate([
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_base64' => 'nullable|string',
        ]);

        try {
            $rawBase64 = null;

            if ($request->filled('image_base64')) {
                // Gambar yang sudah di-downscale oleh browser (sangat cepat & hemat bandwidth)
                $rawBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->input('image_base64'));
            } elseif ($request->hasFile('image')) {
                $file = $request->file('image');
                $rawBase64 = base64_encode(file_get_contents($file->getRealPath()));
            }

            if (empty($rawBase64)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Berkas gambar tidak ditemukan.',
                ], 422);
            }

            // Ambil daftar kategori dan supplier dari database untuk pencocokan AI
            $categories = Category::select('id', 'name')->get();
            $suppliers = Supplier::select('id', 'name')->get();

            $result = $this->qwenService->analyzeProductImage($rawBase64, $categories, $suppliers);

            if (! $result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal menganalisis gambar produk.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rekomendasi visual produk berhasil digenerate oleh AI.',
                'data' => $result['data'],
            ]);
        } catch (\Throwable $e) {
            Log::error('AiAssistantController error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ], 500);
        }
    }
}
