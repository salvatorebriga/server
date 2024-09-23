<x-guest-layout>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('img/boolbnb-logo2.png') }}" alt="Logo" />
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Surname -->
            <div class="mt-4">
                <x-input-label for="surname" :value="__('Surname')" />
                <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')"
                    required autocomplete="surname" />
                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email*')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Date of Birth -->
            <div class="mt-4">
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth"
                    :value="old('date_of_birth')" required autocomplete="bday" />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password*')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password*')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <style scoped>
        body {
            background-color: #646668;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo img {
            width: 50px;
            margin-bottom: 30px;
            display: block;
            /* Centra l'immagine */
            margin-left: auto;
            margin-right: auto;
        }

        form {
            width: 100%;
        }

        label {
            font-weight: 600;
            color: #333;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
            width: 100%;
        }

        input:focus {
            border-color: #4F46E5;
            outline: none;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .ms-4 {
            margin-left: 1rem;
        }
    </style>
</x-guest-layout>
