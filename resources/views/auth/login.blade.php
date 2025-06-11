<x-guest-layout>
<div class="container min-vh-60 d-flex flex-column justify-content-center align-items-center" style="min-height:60vh;">
    <div class="w-100 mt-5" style="max-width: 360px;">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="/" class="auth-logo d-block mb-2" style="font-size:2.2rem; font-weight:700; color:#dc3545; text-decoration:none;">
                1Click<span class="text-dark">AllEat</span>
            </a>
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="bg-white p-3 rounded shadow">

        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control rounded-3 mt-1 w-100" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control rounded-3 mt-1 w-100"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        @if (Route::has('password.request'))
    <div class="mb-2 w-100">
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
    </div>
@endif
<div class="w-100">
    <x-primary-button class="btn btn-dark w-100 py-2 fs-5 fw-bold rounded-3">
        {{ __('Log in') }}
    </x-primary-button>
</div>
        </form>
        
        <!-- Lien vers la page d'inscription -->
        <div class="mt-3 text-center">
            <p>Vous n'avez pas encore de compte ?</p>
            <a href="{{ route('register') }}" class="btn btn-outline-dark rounded-3">
                {{ __('Créer un compte') }}
            </a>
        </div>
    </div>
</div>
</x-guest-layout>
