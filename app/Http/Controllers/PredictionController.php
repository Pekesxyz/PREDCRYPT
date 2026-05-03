<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
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

        // Tentukan path ke script python
        $pythonScript = base_path('python/predict.py');

        // Ambil harga live dan data historis dari CoinGeckoService
        $coinGecko = app(\App\Services\CoinGeckoService::class);
        $livePrice = $coinGecko->getCoinPrice($coinId);
        $historicalData = $coinGecko->getHistoricalPrices($coinId, 30);
        
        // Simpan data historis ke file temporary agar Python bisa membacanya tanpa kena rate limit
        $tempFile = storage_path("app/private/temp_history_{$coinId}.json");
        if (!file_exists(storage_path('app/private'))) {
            mkdir(storage_path('app/private'), 0755, true);
        }
        file_put_contents($tempFile, json_encode($historicalData));

        // Jalankan Process
        // Pastikan python atau python3 dikenali di sistem Anda
        $process = new Process(['python', $pythonScript, $coinId, $livePrice, $tempFile]);
        $process->setTimeout(60); // Timeout 60 detik (karena fetch API + ML)

        try {
            $process->mustRun();
            
            // Ambil output JSON dari Python
            $output = $process->getOutput();
            $result = json_decode($output, true);

            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], 400);
            }

            // Simpan riwayat prediksi ke Database jika user sedang login
            if (Auth::check()) {
                Prediction::create([
                    'user_id' => Auth::id(),
                    'coin' => $coinId,
                    'current_price' => $result['current_price'],
                    'predicted_price' => $result['predicted_price'],
                    'mae' => $result['mae'],
                    'rmse' => $result['rmse']
                ]);
            }

            return response()->json($result);

        } catch (ProcessFailedException $exception) {
            \Log::error('Python Script Error: ' . $exception->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses model Machine Learning.'
            ], 500);
        }
    }
}
