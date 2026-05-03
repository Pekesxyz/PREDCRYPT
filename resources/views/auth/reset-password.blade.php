<x-layout.auth :title="'Reset Password'">
    <h2 class="text-2xl font-bold text-text-primary mb-2">Reset Password</h2>
    <p class="text-sm text-text-secondary mb-6">Silakan masukkan password baru Anda.</p>

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.3)] text-danger text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div>
            <label for="email" class="block text-sm font-medium text-text-secondary mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required readonly
                   class="form-input bg-bg-tertiary opacity-70">
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-text-secondary mb-2">Password Baru</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="password" id="password" required autofocus
                       class="form-input pr-12" placeholder="Minimal 8 karakter">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted hover:text-accent transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49M14.084 14.158a3 3 0 0 1-4.242-4.242M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><line x1="2" x2="22" y1="2" y2="22" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </button>
            </div>
        </div>
        
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-text-secondary mb-2">Konfirmasi Password</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                       class="form-input pr-12" placeholder="Ulangi password baru">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted hover:text-accent transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49M14.084 14.158a3 3 0 0 1-4.242-4.242M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><line x1="2" x2="22" y1="2" y2="22" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </button>
            </div>
        </div>
        
        <button type="submit" class="gradient-btn w-full text-sm">
            <span>Perbarui Password</span>
        </button>
    </form>
</x-layout.auth>

