@extends('layouts.app')

@section('title', 'Register - World of Pets')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <form method="POST" action="{{ route('register') }}">
            @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                    <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password" required autocomplete="new-password" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password_confirmation" required autocomplete="new-password" />
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('login') }}">
                        Already registered?
                    </a>

                    <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection