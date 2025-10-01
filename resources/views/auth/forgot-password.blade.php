@extends('layouts.app')

@section('title', 'Forgot Password - World of Pets')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md lg:max-w-lg mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Forgot Password</h1>
            <p class="text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                       autofocus />
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-4">
                <button type="submit" 
                        class="w-full text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center space-y-2">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 underline block">
                Back to Login
            </a>
            <p class="text-xs text-gray-500">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 underline">
                    Sign up here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection