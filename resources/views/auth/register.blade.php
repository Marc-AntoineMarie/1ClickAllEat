<x-guest-layout>
<div class="container min-vh-60 d-flex flex-column justify-content-center align-items-center" style="min-height:60vh;">
    <div class="w-100 mt-5" style="max-width: 460px;">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="/" class="auth-logo d-block mb-2" style="font-size:2.2rem; font-weight:700; color:#dc3545; text-decoration:none;">
                1Click<span class="text-dark">AllEat</span>
            </a>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="bg-white p-3 rounded shadow">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="form-control rounded-3 mt-1 w-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control rounded-3 mt-1 w-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label class="form-label">Inscription en tant que</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="role_client" value="client" {{ old('role', 'client') == 'client' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="role_client">
                            Client
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="role_restaurateur" value="restaurateur" {{ old('role') == 'restaurateur' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="role_restaurateur">
                            Restaurateur
                        </label>
                    </div>
                </div>
                @error('role')
                    <div class="text-danger mt-2 small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="form-control rounded-3 mt-1 w-100"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="form-control rounded-3 mt-1 w-100"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                <a class="text-decoration-none text-dark" href="{{ route('login') }}">
                    {{ __('Déjà inscrit ?') }}
                </a>

                <x-primary-button class="btn btn-dark py-2 rounded-3 fw-bold">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
</x-guest-layout>
