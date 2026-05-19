<x-guest-layout>
    <style>
        .register-container {
            background: linear-gradient(135deg, #720505 0%, #e30e0e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 420px;
            width: 100%;
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .register-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #700404 0%, #f70505 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(112, 4, 4, 0.4);
        }
        .register-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            text-align: center;
            margin-bottom: 8px;
        }
        .register-subtitle {
            font-size: 14px;
            color: #e62c2c;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f7fafc;
        }
        .form-input:focus {
            outline: none;
            border-color: #e30e0e;
            background: white;
            box-shadow: 0 0 0 3px rgba(227, 14, 14, 0.1);
        }
        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
            display: block;
        }
        .register-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #971f0d 0%, #e63b3b 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(151, 31, 13, 0.3);
        }
        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(151, 31, 13, 0.4);
        }
        .login-link {
            color: #e30e0e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .login-link:hover {
            color: #f70505;
        }
    </style>

    <div class="register-container">
        <div class="register-card">
            <div class="register-logo">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            
            <h2 class="register-title">Create Account</h2>
            <p class="register-subtitle">Join us — create an account to get started</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label class="form-label" for="name">{{ __('Full Name') }}</label>
                    <input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label class="form-label" for="email">{{ __('Email Address') }}</label>
                    <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label class="form-label" for="password">{{ __('Password') }}</label>
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Create a password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <a class="login-link" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="register-button">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
