<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form lupa password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim email link reset password
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Kirim Email (Menggunakan Mailable bawaan atau manual)
        // Untuk kecepatan, kita gunakan Mail::send manual
        Mail::send('auth.emails.password-reset', ['token' => $token, 'email' => $request->email], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Notification - PREDCRYPT');
        });

        return back()->with('status', 'Link reset password telah dikirim ke Gmail Anda!');
    }

    /**
     * Tampilkan form reset password
     */
    public function showResetForm($token, Request $request)
    {
        $record = DB::table('password_reset_tokens')
                    ->where(['email' => $request->email, 'token' => $token])
                    ->first();

        if (!$record) {
            return redirect()->route('password.request')->withErrors(['email' => 'Link reset tidak valid!']);
        }

        // Cek kedaluwarsa (60 menit)
        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
            return redirect()->route('password.request')->withErrors(['email' => 'Link reset sudah kedaluwarsa (lebihi 60 menit). Silakan minta link baru.']);
        }

        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Proses reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
                            ->where([
                                'email' => $request->email,
                                'token' => $request->token
                            ])
                            ->first();

        if(!$record){
            return back()->withErrors(['email' => 'Token tidak valid!']);
        }

        // Cek kedaluwarsa lagi saat submit (untuk keamanan ekstra)
        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
            return redirect()->route('password.request')->withErrors(['email' => 'Link sudah kedaluwarsa.']);
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect()->route('login')->with('status', 'Password Anda telah berhasil diperbarui!');
    }
}
