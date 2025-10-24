@extends('layouts.app')

@section('title', 'Login - World of Pets')

@section('content')
@push('styles')
<style>
    /* Reuse same paw background and card styles as register */
    .pet-page-bg { background-color: #fff8f9; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48'%3E%3Cg fill='%23ffdfe6' fill-opacity='0.18'%3E%3Cpath d='M12 20c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4zM20 12c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4zM36 12c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4zM28 20c0 2.2-1.8 4-4 4s-4-1.8-4-4 1.8-4 4-4 4 1.8 4 4z'/%3E%3C/g%3E%3C/svg%3E"); background-repeat: repeat; background-size: 120px 120px; }
    .pet-card { background: linear-gradient(180deg, #ffffff 0%, #fffafc 100%); border-radius: 12px; box-shadow: 0 10px 30px rgba(15,23,42,0.08); position: relative; overflow: hidden; border: 1px solid rgba(0,0,0,0.04); }
    .pet-card::after { content: ''; position: absolute; right: -20%; top: -10%; width: 260px; height: 260px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cg fill='%23ffdfe6' fill-opacity='0.12'%3E%3Cpath d='M50 80c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16zM80 50c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16zM140 50c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16zM110 80c0 8.8-7.2 16-16 16s-16-7.2-16-16 7.2-16 16-16 16 7.2 16 16z'/%3E%3C/g%3E%3C/svg%3E"); background-repeat: no-repeat; opacity: 0.7; transform: rotate(-12deg) scale(1.05); pointer-events: none; }
    .btn-pet-primary { background: linear-gradient(90deg, var(--color-primary) 0%, rgba(255,99,127,0.95) 100%); color: #fff; border: none; box-shadow: 0 6px 18px rgba(255,99,127,0.12); }

    /* Button lift on hover */
    .btn-pet-primary { transition: transform 220ms cubic-bezier(.2,.9,.2,1), box-shadow 220ms cubic-bezier(.2,.9,.2,1); }
    .btn-pet-primary:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(255,99,127,0.18); }

    @keyframes paw-shimmer { 0% { transform: rotate(-12deg) scale(1.03) translateY(0); opacity: 0.6; } 50% { transform: rotate(-10deg) scale(1.06) translateY(-4px); opacity: 0.8; } 100% { transform: rotate(-12deg) scale(1.03) translateY(0); opacity: 0.6; } }
    .pet-card::after { animation: paw-shimmer 6s ease-in-out infinite; }
</style>
@endpush

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 pet-page-bg">
    <div class="w-full sm:max-w-md lg:max-w-lg mt-6 px-6 py-8 pet-card">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-sm text-gray-600">Sign in to your account to continue</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

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
                       autofocus 
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
                       placeholder="Enter your password"
                       required 
                       autocomplete="current-password" />
                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                <div class="mt-2 text-right">
                    <a href="{{ route('password.request') }}" class="text-sm text-pink-600 hover:underline">Forgot your password?</a>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

            
            </div>

            <div class="flex flex-col space-y-4">
                <button type="submit" 
                        class="w-full btn-pet-primary hover:opacity-95 focus:ring-4 focus:outline-none focus:ring-pink-200 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">
                    Sign In
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                    Create an account
                </a>
            </p>
        </div>
    </div>
</div>
@endsection