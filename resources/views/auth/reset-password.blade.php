@section('content')
<div class="mb-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 pb-12 pet-page-bg">
    <div class="w-full sm:max-w-md lg:max-w-lg mt-6 mb-8 px-6 py-8 pet-card">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Reset your password</h1>
            <p class="text-sm text-gray-600">Choose a new secure password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700 mb-2">Email</label>
                <input id="email" class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-sm text-gray-700 mb-2">Password</label>
                <input id="password" class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" type="password" name="password" required autocomplete="new-password" minlength="8" />
                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" class="block w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150" type="password" name="password_confirmation" required autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-4">
                <button type="submit" class="w-full btn-pet-primary hover:opacity-95 focus:ring-4 focus:outline-none focus:ring-pink-200 font-semibold rounded-lg text-base px-6 py-2.5 text-center transition duration-150 h-[46px] flex items-center justify-center">Reset Password</button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Remembered your password? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">Sign in</a></p>
        </div>
    </div>
</div>
@endsection