<x-guest-layout>
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('img/boolbnb-logo2.png') }}" alt="Logo" />
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="block input-group">
                <x-input-label for="email" :value="__('Email')" />
                <div class="input-wrapper">
                    <i class="fa fa-user input-icon"></i>
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                        autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="block input-group">
                <x-input-label for="password" :value="__('Password')" />
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <x-text-input id="password" type="password" name="password" required
                        autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                    <span class="ms-2 text-muted">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center mt-4">
                @if (Route::has('password.request'))
                    <a class="link text-sm" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center mt-6 justify-between">
                <a class="link text-sm" href="{{ route('register') }}">
                    {{ __('Don\'t have an account? Register') }}
                </a>

                <button type="submit" class="btn-primary ms-3">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>

    <style scoped>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

        body {
            background-color: #f0f4f8;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo img {
            width: 50px;
            margin-bottom: 30px;
        }

        form {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        input,
        label {
            display: block;
            width: 100%;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #4F46E5;
            outline: none;
        }

        .block {
            margin-bottom: 20px;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
        }

        .input-icon {
            font-size: 1.2rem;
            /* Dimensione rimpicciolita */
            color: #4F46E5;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #4F46E5;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #3730a3;
        }

        .link {
            color: #4F46E5;
            text-decoration: none;
            transition: color 0.3s;
        }

        .link:hover {
            color: #3730a3;
        }

        .text-muted {
            color: #6B7280;
            font-size: 0.9rem;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mt-6,
        .mt-4 {
            margin-top: 1.5rem;
        }

        .ms-2 {
            margin-left: 0.5rem;
        }
    </style>
</x-guest-layout>
