@extends('layouts.app')

@section('title', 'Register - World of Pets')

@push('styles')
<style>
    /* Page-level paw background */
    .pet-page-bg {
        background-color: #fff8f9; /* soft warm backdrop */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48'%3E%3Cg fill='%23ffdfe6' fill-opacity='0.18'%3E%3Cpath d='M12 20c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4zM20 12c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4zM36 12c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4zM28 20c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4z'/%3E%3C/g%3E%3C/svg%3E");
        background-repeat: repeat;
        background-size: 120px 120px;
    }

    /* Card style with subtle paw overlay */
    .pet-card {
        background: linear-gradient(180deg, #ffffff 0%, #fffafc 100%);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(15,23,42,0.08);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
    }

    /* Slight paw accent on the card - faint and decorative */
    .pet-card::after {
        content: '';
        position: absolute;
        right: -20%;
        top: -10%;
        width: 260px;
        height: 260px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cg fill='%23ffdfe6' fill-opacity='0.12'%3E%3Cpath d='M50 80c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16zM80 50c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16zM140 50c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16zM110 80c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16z'/%3E%3C/g%3E%3C/svg%3E");
        background-repeat: no-repeat;
        opacity: 0.7;
        transform: rotate(-12deg) scale(1.05);
        pointer-events: none;
    }

    /* Friendlier primary button */
    .btn-pet-primary {
        background: linear-gradient(90deg, var(--color-primary) 0%, rgba(255,99,127,0.95) 100%);
        color: #fff;
        border: none;
        box-shadow: 0 6px 18px rgba(255,99,127,0.12);
    }

    /* Button lift on hover */
    .btn-pet-primary {
        transition: transform 220ms cubic-bezier(.2,.9,.2,1), box-shadow 220ms cubic-bezier(.2,.9,.2,1);
    }
    .btn-pet-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(255,99,127,0.18);
    }

    /* Paw shimmer animation for the decorative paw image */
    @keyframes paw-shimmer {
        0% { transform: rotate(-12deg) scale(1.03) translateY(0); opacity: 0.6; }
        50% { transform: rotate(-10deg) scale(1.06) translateY(-4px); opacity: 0.8; }
        100% { transform: rotate(-12deg) scale(1.03) translateY(0); opacity: 0.6; }
    }
    .pet-card::after {
        animation: paw-shimmer 6s ease-in-out infinite;
    }

    /* Reduce card padding on small screens for comfortable spacing */
    @media (max-width: 640px) {
        .pet-card { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="mb-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 pb-12 pet-page-bg">
    <div class="w-full sm:max-w-md lg:max-w-lg mt-6 mb-8 px-6 py-8 pet-card">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h1>
            <p class="text-sm text-gray-600">Join the World of Pets community</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-medium text-sm text-gray-700 mb-2">Full Name</label>
                <input id="name" 
                       class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Enter your full name"
                       required 
                       autofocus 
                       autocomplete="name" />
                @error('name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700 mb-2">Email Address</label>
                <input id="email" 
                       class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email address"
                       required 
                       autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-sm text-gray-700 mb-2">Password</label>
                <input id="password" 
                       class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" 
                       type="password" 
                       name="password" 
                       placeholder="Create a password"
                       required 
                       autocomplete="new-password"
                       pattern="(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]+"
                       title="Password must be alphanumeric and contain at least one letter and one number." />
                <p id="password-help" class="text-xs text-gray-500 mt-2">Password must be alphanumeric and contain at least one letter and one number.</p>
                <p id="password-error" class="text-red-500 text-sm mt-2 hidden">Password must be alphanumeric and contain at least one letter and one number.</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" 
                       class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" 
                       type="password" 
                       name="password_confirmation" 
                       placeholder="Confirm your password"
                       required 
                       autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-4">
                <button type="submit" 
                        class="w-full btn-pet-primary hover:opacity-95 focus:ring-4 focus:outline-none focus:ring-pink-200 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">
                    Create Account
                </button>
            </div>
        </form>

        @push('scripts')
        <script>
            (function(){
                const form = document.querySelector('form[action="{{ route('register') }}"]');
                if (!form) return;

                const pwd = form.querySelector('#password');
                const pwdConfirm = form.querySelector('#password_confirmation');
                const pwdError = document.getElementById('password-error');

                const alphaNumRegex = /^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]+$/;

                function showPwdError(msg){
                    pwdError.textContent = msg;
                    pwdError.classList.remove('hidden');
                }
                function hidePwdError(){
                    pwdError.classList.add('hidden');
                }

                form.addEventListener('submit', function(e){
                    hidePwdError();

                    const value = pwd.value || '';
                    if (!alphaNumRegex.test(value)){
                        e.preventDefault();
                        showPwdError('Password must be alphanumeric and contain at least one letter and one number.');
                        pwd.focus();
                        return false;
                    }

                    if (pwdConfirm && pwdConfirm.value !== value){
                        e.preventDefault();
                        showPwdError('Password and confirmation do not match.');
                        pwdConfirm.focus();
                        return false;
                    }
                });

                // Live feedback
                pwd.addEventListener('input', function(){
                    if (alphaNumRegex.test(pwd.value)){
                        hidePwdError();
                    }
                });
                if (pwdConfirm){
                    pwdConfirm.addEventListener('input', function(){
                        if (pwdConfirm.value === pwd.value){
                            hidePwdError();
                        }
                    });
                }
            })();
        </script>
        @endpush

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection