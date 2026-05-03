<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use App\Services\CoinGeckoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    protected CoinGeckoService $coinGecko;

    public function __construct(CoinGeckoService $coinGecko)
    {
        $this->coinGecko = $coinGecko;
    }

    /**
     * Tambah/Hapus dari favorit
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'coin' => 'required|string'
        ]);

        $preference = Preference::where('user_id', Auth::id())
                                ->where('favorite_coin', $request->coin)
                                ->first();

        if ($preference) {
            $preference->delete();
            return back()->with('success', 'Koin dihapus dari favorit');
        } else {
            Preference::create([
                'user_id' => Auth::id(),
                'favorite_coin' => $request->coin
            ]);
            return back()->with('success', 'Koin ditambahkan ke favorit');
        }
    }
}
