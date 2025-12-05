<x-guest-layout>
    <h1>mCRM Login</h1>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success show">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group">
            <label>
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
        </div>

        <button type="submit" class="submit-btn">
            <span class="btn-text">Log in</span>
        </button>

        @if (Route::has('password.request'))
            <p style="margin-top: 15px; text-align: center;">
                <a href="{{ route('password.request') }}" style="color: var(--accent); text-decoration: none;">
                    Forgot your password?
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>

