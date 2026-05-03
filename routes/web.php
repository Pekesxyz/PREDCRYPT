<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| PREDCRYPT Routes
|--------------------------------------------------------------------------
*/

// Public pages
Route::get('/', function() {
    $coinGecko = app(\App\Services\CoinGeckoService::class);
    $coins = $coinGecko->getCurrentPrices();
    return view('pages.home', compact('coins'));
})->name('home');
Route::get('/market', [App\Http\Controllers\MarketController::class, 'index'])->name('market');
Route::get('/prediction', function() {
    $alerts = collect();
    if (Auth::check()) {
        $alerts = \App\Models\PriceAlert::where('user_id', Auth::id())->latest()->get();
    }
    return view('pages.prediction', compact('alerts'));
})->name('prediction');
Route::get('/about', fn() => view('pages.about'))->name('about');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    });

    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect(route('home'));
    });

    // Forgot Password Routes
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect(route('home'));
})->name('logout');

// Protected pages
Route::middleware('auth')->group(function () {
    Route::get('/history', [App\Http\Controllers\HistoryController::class, 'index'])->name('history');
    
    Route::post('/preferences/toggle', [App\Http\Controllers\PreferenceController::class, 'toggle'])->name('preferences.toggle');
    
    Route::post('/notifications', [App\Http\Controllers\NotificationController::class, 'store'])->name('notifications.store');
    Route::put('/notifications/{alert}/toggle', [App\Http\Controllers\NotificationController::class, 'toggle'])->name('notifications.toggle');
    Route::delete('/notifications/{alert}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// API Endpoints
Route::post('/api/predict', [App\Http\Controllers\PredictionController::class, 'predict'])->name('api.predict');
Route::get('/api/check-alerts', [App\Http\Controllers\AlertCheckController::class, 'check'])->name('api.check_alerts');
Route::get('/api/prices', function() {
    return response()->json(app(\App\Services\CoinGeckoService::class)->getCurrentPrices());
})->name('api.prices');
