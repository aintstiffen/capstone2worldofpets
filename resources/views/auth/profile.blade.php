@extends('layouts.app')

@section('title', 'Profile - World of Pets')

@section('content')
<div class="container mx-auto py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">My Profile</h1>
            
            </div>

            <div class="border-t border-gray-200 pt-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h2>
                        
                        @if (session('status') === 'profile-updated')
                            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded">
                                Profile information updated successfully!
                            </div>
                        @endif
                        
                        @if ($errors->has('profile_error'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded">
                                {{ $errors->first('profile_error') }}
                            </div>
                        @endif
                        
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            
                            <div class="flex flex-col items-center mb-6">
                                <div class="w-32 h-32 rounded-full overflow-hidden mb-4">
                                    <img src="{{ auth()->user()->getProfilePictureUrl() }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                </div>
                                
                                <div class="w-full">
                                    <label for="profile_picture" class="block font-medium text-gray-700 mb-1">Profile Picture</label>
                                    <input id="profile_picture" name="profile_picture" type="file" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                        accept="image/*" />
                                    @error('profile_picture')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">JPG, PNG or GIF (max. 2MB)</p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="name" class="block font-medium text-gray-700 mb-1">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required autocomplete="name" />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required autocomplete="username" />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#050708]/50 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div>
                        
                        @if (session('status') === 'password-updated')
                            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded">
                                Password updated successfully!
                            </div>
                        @endif
                        
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')
                            
                            <div>
                                <label for="current_password" class="block font-medium text-gray-700 mb-1">Current Password</label>
                                <input id="current_password" name="current_password" type="password" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required autocomplete="current-password" />
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block font-medium text-gray-700 mb-1">New Password</label>
                                <input id="password" name="password" type="password" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required autocomplete="new-password" />
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required autocomplete="new-password" />
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="text-white bg-[#050708] hover:bg-[#050708]/90 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#050708]/50 dark:hover:bg-[#050708]/30 me-2 mb-2">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Your Pet Assessments</h2>
                
                @if(auth()->user()->assessments->count() > 0)
                    <div class="space-y-4">
                        @foreach(auth()->user()->assessments as $assessment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="font-medium">{{ ucfirst($assessment->pet_type) }} Assessment</h3>
                                        <p class="text-gray-500 text-sm">{{ $assessment->created_at->format('F j, Y') }}</p>
                                    </div>
                                    <a href="{{ route('assessment', ['id' => $assessment->id]) }}" 
                                       class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                        View Results
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">You haven't completed any pet personality assessments yet.</p>
                    <a href="{{ route('assessment') }}" class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Take Assessment
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection