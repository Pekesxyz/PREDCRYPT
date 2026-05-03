<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Tampilkan riwayat prediksi milik user
     */
    public function index(Request $request)
    {
        $query = Prediction::where('user_id', Auth::id())->latest();

        if ($request->has('coin') && $request->coin !== 'all') {
            $query->where('coin', $request->coin);
        }

        $histories = $query->paginate(10);

        return view('pages.history', [
            'histories' => $histories,
            'currentFilter' => $request->coin ?? 'all'
        ]);
    }
}
