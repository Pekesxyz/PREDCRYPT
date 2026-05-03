<?php

namespace App\Http\Controllers;

use App\Services\CoinGeckoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    protected CoinGeckoService $coinGecko;

    public function __construct(CoinGeckoService $coinGecko)
    {
        $this->coinGecko = $coinGecko;
    }

    /**
     * Tampilkan halaman market dengan data live
     */
    public function index()
    {
        $initialCoins = $this->coinGecko->getCurrentPrices();

        $favorites = [];
        if (Auth::check()) {
            $favorites = \App\Models\Preference::where('user_id', Auth::id())->pluck('favorite_coin')->toArray();
        }

        return view('pages.market', [
            'initialCoins' => json_encode($initialCoins),
            'favorites' => json_encode($favorites)
        ]);
    }
}
