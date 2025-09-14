@extends('layouts.app')

@section('title', 'Authentication Required - World of Pets')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md lg:max-w-lg mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Authentication Required') }}</h1>
            <p class="text-sm text-gray-600">Please sign in to continue</p>
        </div>

        <!-- Content -->
        <div class="text-center mb-8">
            <p class="text-gray-700 leading-relaxed">
                {{ __('You need to be logged in to access this page. This helps us save your results and provide personalized recommendations.') }}
            </p>
        </div>
        
        <!-- Buttons -->
        <div class="flex flex-col space-y-4">
            <a href="{{ route('login') }}" 
               class="w-full text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">
                {{ __('Sign In') }}
            </a>
            <a href="{{ route('register') }}" 
               class="w-full text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">
                {{ __('Create an Account') }}
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                ← Go back
            </a>
        </div>
    </div>
</div>
@endsection