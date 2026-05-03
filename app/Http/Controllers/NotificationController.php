<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'coin' => 'required|string',
            'target_price' => 'required|numeric|min:0',
            'direction' => 'required|in:above,below'
        ]);

        PriceAlert::create([
            'user_id' => Auth::id(),
            'coin' => $request->coin,
            'target_price' => $request->target_price,
            'direction' => $request->direction,
            'is_triggered' => false
        ]);

        return back()->with('success', 'Price Alert berhasil dibuat!');
    }

    public function toggle(PriceAlert $alert)
    {
        // Pastikan milik user yang login
        if ($alert->user_id !== Auth::id()) {
            abort(403);
        }

        $alert->update(['is_triggered' => !$alert->is_triggered]);
        
        $status = $alert->is_triggered ? 'dinonaktifkan' : 'diaktifkan';
        return back()->with('success', "Alert berhasil $status");
    }

    public function destroy(PriceAlert $alert)
    {
        if ($alert->user_id !== Auth::id()) {
            abort(403);
        }

        $alert->delete();
        return back()->with('success', 'Alert berhasil dihapus');
    }
}
