@extends('layouts.app')

@section('title', 'Authentication Required - World of Pets')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 text-white py-4 px-6">
            <h1 class="text-xl font-bold">{{ __('Authentication Required') }}</h1>
        </div>

        <div class="p-6">
            <p class="text-gray-700 mb-6">{{ __('You need to be logged in to access the pet personality assessment. This helps us save your results and provide personalized recommendations.') }}</p>
            
            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-md text-center hover:bg-blue-700 transition-colors">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}" class="inline-block px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-md text-center hover:bg-gray-50 transition-colors">
                    {{ __('Create an Account') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection