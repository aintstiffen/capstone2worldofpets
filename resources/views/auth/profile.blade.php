@extends('layouts.app')

@section('title', 'Profile - World of Pets')

@section('content')
<style>
    :root {
        /* Material Design Color Palette */
        --md-primary: #6200EE;
        --md-primary-variant: #3700B3;
        --md-secondary: #03DAC6;
        --md-on-primary: #FFFFFF;
        --md-on-surface: #000000;
        --md-surface: #FFFFFF;
        --md-background: #F5F5F5;
        
        /* Material Design Elevation Shadows */
        --md-elevation-1: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        --md-elevation-2: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        --md-elevation-3: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        --md-elevation-4: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        --md-elevation-6: 0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22);
    }
    
    .profile-container {
        background: var(--md-background);
        min-height: calc(100vh - 80px);
        padding: 40px 16px;
    }
    
    .profile-card {
        background: var(--md-surface);
        border-radius: 16px;
        box-shadow: var(--md-elevation-2);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .profile-header {
        background: linear-gradient(135deg, var(--md-primary) 0%, var(--md-primary-variant) 100%);
        padding: 32px;
        color: var(--md-on-primary);
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .section-card {
        background: var(--md-surface);
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--md-elevation-1);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .section-card:hover {
        box-shadow: var(--md-elevation-3);
        transform: translateY(-2px);
    }
    
    .avatar-container {
        position: relative;
        margin: -60px auto 24px;
        width: 140px;
        height: 140px;
        z-index: 10;
    }
    
    .avatar-display {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--md-surface);
        padding: 8px;
        box-shadow: var(--md-elevation-4);
        border: 4px solid var(--md-surface);
    }
    
    .avatar-option input:checked + label div {
        border-color: var(--md-primary);
        box-shadow: var(--md-elevation-3);
        transform: scale(1.05);
    }
    
    .avatar-option label div {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        border: 2px solid #E0E0E0;
    }
    
    .avatar-option label div:hover {
        border-color: var(--md-secondary);
        box-shadow: var(--md-elevation-2);
    }
    
    .md-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: var(--md-surface);
    }
    
    .md-input:focus {
        outline: none;
        border-color: var(--md-primary);
        box-shadow: 0 0 0 3px rgba(98, 0, 238, 0.1);
    }
    
    .md-input:disabled {
        background: #F5F5F5;
        cursor: not-allowed;
    }
    
    .md-label {
        display: block;
        font-weight: 500;
        font-size: 0.875rem;
        color: #424242;
        margin-bottom: 8px;
        letter-spacing: 0.25px;
    }
    
    .md-button {
        background: var(--md-primary);
        color: var(--md-on-primary);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        box-shadow: var(--md-elevation-2);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .md-button:hover {
        background: var(--md-primary-variant);
        box-shadow: var(--md-elevation-4);
        transform: translateY(-2px);
    }
    
    .md-button:active {
        transform: translateY(0);
        box-shadow: var(--md-elevation-2);
    }
    
    .md-alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--md-elevation-1);
    }
    
    .md-alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        border-left: 4px solid #4CAF50;
    }
    
    .md-alert-error {
        background: #FFEBEE;
        color: #C62828;
        border-left: 4px solid #F44336;
    }
    
    .assessment-card {
        background: linear-gradient(135deg, #F5F5F5 0%, #FAFAFA 100%);
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid var(--md-secondary);
        box-shadow: var(--md-elevation-1);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .assessment-card:hover {
        box-shadow: var(--md-elevation-3);
        transform: translateX(4px);
    }
</style>

<div class="profile-container">
    <div class="max-w-4xl mx-auto">
        <div class="profile-card">
            <div class="profile-header">
                <h1 class="text-3xl font-bold text-center relative z-10">My Profile</h1>
                <p class="text-center mt-2 opacity-90 relative z-10">Manage your account settings and preferences</p>
            </div>
            
            <div class="avatar-container">
                <div class="avatar-display">
                    <img src="{{ auth()->user()->getProfilePictureUrl() }}" 
                         alt="{{ auth()->user()->name }}'s avatar" 
                         class="w-full h-full object-contain rounded-full" id="current-avatar">
                </div>
            </div>
            
            <div class="px-6 pb-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Profile Information Section -->
                    <div class="section-card">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <span class="text-2xl">👤</span>
                            Profile Information
                        </h2>
                        
                        @if (session('status') === 'profile-updated')
                            <div class="md-alert md-alert-success">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Profile updated successfully!</span>
                            </div>
                        @endif
                        
                        @if ($errors->has('profile_error'))
                            <div class="md-alert md-alert-error">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $errors->first('profile_error') }}</span>
                            </div>
                        @endif
                        
                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6 flex flex-col h-full">
                            @csrf
                            @method('patch')
                            
                            <div class="flex-grow space-y-6">
                                <div>
                                    <label class="md-label">🎨 Choose Avatar Style</label>
                                    <input type="hidden" name="avatar_style" id="selected_avatar_style" value="{{ auth()->user()->avatar_style }}">
                                    
                                    <div class="grid grid-cols-5 gap-3">
                                        @foreach($avatarStyles as $styleKey => $styleUrl)
                                            <div class="avatar-option">
                                                <input type="radio" name="avatar_style" id="style-{{ $styleKey }}" 
                                                    value="{{ $styleKey }}" class="hidden avatar-radio" 
                                                    {{ auth()->user()->avatar_style === $styleKey ? 'checked' : '' }}
                                                    data-preview-url="{{ $styleUrl . md5(auth()->user()->id . auth()->user()->name) }}"
                                                    onclick="document.getElementById('selected_avatar_style').value='{{ $styleKey }}'">
                                                <label for="style-{{ $styleKey }}" class="cursor-pointer block">
                                                    <div class="w-full aspect-square overflow-hidden">
                                                        <img src="{{ $styleUrl . md5(auth()->user()->id . auth()->user()->name) }}" 
                                                            alt="{{ $styleKey }} style" class="w-full h-full object-contain">
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="name" class="md-label">📝 Name</label>
                                    <input id="name" name="name" type="text" value="{{ auth()->user()->name }}" 
                                        class="md-input" required />
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email-display" class="md-label">✉️ Email</label>
                                    <input id="email-display" type="email" value="{{ auth()->user()->email }}" 
                                        class="md-input" disabled />
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}" />
                                    <p class="mt-2 text-xs text-gray-500 italic">Email cannot be changed for security reasons</p>
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <button type="submit" class="md-button w-full">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Change Password Section -->
                    <div class="section-card">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <span class="text-2xl">🔒</span>
                            Change Password
                        </h2>
                        
                        @if (session('status') === 'password-updated')
                            <div class="md-alert md-alert-success">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Password updated successfully!</span>
                            </div>
                        @endif
                        
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6 flex flex-col h-full">
                            @csrf
                            @method('put')
                            
                            <div class="flex-grow space-y-6">
                                <div>
                                    <label for="current_password" class="md-label">Current Password</label>
                                    <input id="current_password" name="current_password" type="password" 
                                        class="md-input" required autocomplete="current-password" />
                                    @error('current_password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password" class="md-label">New Password</label>
                                    <input id="password" name="password" type="password" 
                                        class="md-input" required autocomplete="new-password" />
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="md-label">Confirm Password</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" 
                                        class="md-input" required autocomplete="new-password" />
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <button type="submit" class="md-button w-full">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pet Assessments Section -->
            <div class="px-6 pb-8 mt-3 mb-3">
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="text-2xl">🐾</span>
                        Your Pet Assessments
                    </h2>
                    
                    @if(auth()->user()->assessments->count() > 0)
                        <div class="space-y-4">
                            @foreach(auth()->user()->assessments as $assessment)
                                <div class="assessment-card">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-800 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $assessment->created_at->format('F j, Y') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">Assessment ID: #{{ $assessment->id }}</p>
                                        </div>
                                        <a href="{{ route('assessment', ['id' => $assessment->id]) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-black rounded-lg font-medium hover:bg-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                            View Details
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-6xl mb-4">🐶</div>
                            <p class="text-gray-600 mb-6">You haven't completed any pet personality assessments yet.</p>
                            <a href="{{ route('assessment') }}" class="md-button inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Take Your First Assessment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle avatar preview when selecting different styles
    document.addEventListener('DOMContentLoaded', function() {
        const avatarRadios = document.querySelectorAll('.avatar-radio');
        const currentAvatar = document.getElementById('current-avatar');
        const selectedAvatarStyleField = document.getElementById('selected_avatar_style');
        
        avatarRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Update the preview
                    currentAvatar.src = this.dataset.previewUrl;
                    // Update the hidden field
                    selectedAvatarStyleField.value = this.value;
                    console.log('Avatar style selected:', this.value);
                }
            });
        });
        
        // Debug output for form submission
        const form = document.querySelector('form[action="{{ route("profile.update") }}"]');
        form.addEventListener('submit', function(e) {
            console.log('Form is being submitted');
            console.log('Selected avatar style:', selectedAvatarStyleField.value);
            // Don't prevent default - let the form submit normally
        });
    });
</script>
@endsection