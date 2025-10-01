@extends('layouts.app')

@section('title', 'Login - World of Pets')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md lg:max-w-lg mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg">
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
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 underline" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <div class="flex flex-col space-y-4">
                <button type="submit" 
                        class="w-full text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">
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