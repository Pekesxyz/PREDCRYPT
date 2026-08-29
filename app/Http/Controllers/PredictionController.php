<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Prediction;
use Illuminate\Support\Facades\Auth;

class PredictionController extends Controller
{
    /**
     * Jalankan prediksi harga koin via Python script
     */
    public function predict(Request $request)
    {
        $request->validate([
            'coin' => 'required|string|in:bitcoin,ethereum,solana,binancecoin'
        ]);

        $coinId = $request->coin;

        // Ambil harga live dan data historis dari CoinGeckoService
        $coinGecko = app(\App\Services\CoinGeckoService::class);
        $livePrice = $coinGecko->getCoinPrice($coinId);
        $historicalData = $coinGecko->getHistoricalPrices($coinId, 30);
        
        // Format historical data to match Python expectation: list of [timestamp, price]
        $formattedHistory = [];
        if (is_array($historicalData)) {
            foreach ($historicalData as $d) {
                if (isset($d['timestamp']) && isset($d['price'])) {
                    $formattedHistory[] = [$d['timestamp'], $d['price']];
                }
            }
        }

        // Dapatkan URL API Hugging Face dari .env
        $apiUrl = env('HUGGINGFACE_API_URL', 'http://127.0.0.1:8000') . '/predict';

        try {
            // Panggil API Python di Hugging Face
            $response = Http::timeout(60)->post($apiUrl, [
                'coin' => $coinId,
                'live_price' => $livePrice,
                'historical_data' => !empty($formattedHistory) ? $formattedHistory : null
            ]);

            if ($response->failed()) {
                $errorMsg = $response->json('detail') ?? 'Terjadi kesalahan saat menghubungi API Prediksi.';
                return response()->json(['error' => $errorMsg], $response->status());
            }

            $result = $response->json();

            // Simpan riwayat prediksi ke Database jika user sedang login
            if (Auth::check() && isset($result['predicted_price'])) {
                Prediction::create([
                    'user_id' => Auth::id(),
                    'coin' => $coinId,
                    'current_price' => $result['current_price'] ?? 0,
                    'predicted_price' => $result['predicted_price'],
                    'mae' => $result['mae'] ?? 0,
                    'rmse' => $result['rmse'] ?? 0
                ]);
            }

            return response()->json($result);

        } catch (\Exception $exception) {
            \Log::error('API Prediction Error: ' . $exception->getMessage());
            return response()->json([
                'error' => 'Gagal menghubungi server Machine Learning (Hugging Face).'
            ], 500);
        }
    }
}
