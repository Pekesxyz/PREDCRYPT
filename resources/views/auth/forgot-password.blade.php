<x-layout.auth :title="'Lupa Password'">
    <h2 class="text-2xl font-bold text-text-primary mb-2">Lupa Password?</h2>
    <p class="text-sm text-text-secondary mb-6">Masukkan email Anda untuk menerima link reset password.</p>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-[rgba(34,197,94,0.1)] border border-[rgba(34,197,94,0.3)] text-success text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.3)] text-danger text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-text-secondary mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="form-input" placeholder="nama@email.com">
        </div>
        
        <button type="submit" class="gradient-btn w-full text-sm">
            <span>Kirim Link Reset</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-text-secondary">
        Ingat password Anda?
        <a href="{{ route('login') }}" class="text-accent hover:underline font-medium">Kembali ke Login</a>
    </p>
</x-layout.auth>

