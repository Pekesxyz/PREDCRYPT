<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use App\Services\CoinGeckoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertCheckController extends Controller
{
    protected CoinGeckoService $coinGecko;

    public function __construct(CoinGeckoService $coinGecko)
    {
        $this->coinGecko = $coinGecko;
    }

    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['triggered' => []]);
        }

        // Ambil alert yang belum ke-trigger
        $activeAlerts = PriceAlert::where('user_id', Auth::id())
                                  ->where('is_triggered', false)
                                  ->get();

        if ($activeAlerts->isEmpty()) {
            return response()->json(['triggered' => []]);
        }

        // Ambil harga live saat ini
        $livePrices = $this->coinGecko->getCurrentPrices();
        // Buat mapping id => price agar mudah
        $priceMap = [];
        foreach ($livePrices as $coin) {
            $priceMap[$coin['id']] = $coin['price'];
        }

        $triggeredAlerts = [];

        foreach ($activeAlerts as $alert) {
            $currentPrice = $priceMap[$alert->coin] ?? null;
            if ($currentPrice === null) continue;

            $isHit = false;
            if ($alert->direction === 'above' && $currentPrice >= $alert->target_price) {
                $isHit = true;
            } elseif ($alert->direction === 'below' && $currentPrice <= $alert->target_price) {
                $isHit = true;
            }

            if ($isHit) {
                // Hapus alert dari database secara otomatis karena sudah tercapai
                $alert->delete();

                $triggeredAlerts[] = [
                    'id' => $alert->id,
                    'coin' => $alert->coin,
                    'target_price' => $alert->target_price,
                    'current_price' => $currentPrice,
                    'direction' => $alert->direction
                ];
            }
        }

        return response()->json(['triggered' => $triggeredAlerts]);
    }
}
