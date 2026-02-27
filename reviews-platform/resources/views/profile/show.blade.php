@extends('layouts.admin')

@section('title', __('profile.title') . ' - ' . __('app.name'))
@section('page-title', __('profile.title'))
@section('page-description', __('profile.description'))

@section('content')
<div class="fade-in">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Profile Photo Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-700 dark:to-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <i class="fas fa-camera mr-2"></i>
                    {{ __('profile.profile_photo') }}
                </h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                    <!-- Current Photo -->
                    <div class="relative">
                        @if($user->photo)
                            <img src="{{ $user->photo_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-purple-100"
                                 id="profilePhotoPreview">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center border-4 border-purple-100"
                                 id="profilePhotoPreview">
                                <span class="text-white font-bold text-4xl">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        
                        @if($user->photo)
                        <form action="{{ route('profile.photo.delete') }}" method="POST" class="absolute -bottom-2 -right-2 delete-photo-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg transition-colors flex items-center justify-center delete-photo-btn">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    
                    <!-- Upload Form -->
                    <div class="flex-1 w-full sm:w-auto">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ __('profile.change_photo') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('profile.photo_formats') }}</p>
                        
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <label class="cursor-pointer">
                                    <input type="file" name="photo" class="hidden" accept="image/*" 
                                           onchange="previewPhoto(event); document.getElementById('photoForm').submit();">
                                    <span class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                        <i class="fas fa-upload mr-2"></i>
                                        {{ __('profile.choose_photo') }}
                                    </span>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-700 dark:to-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <i class="fas fa-user-edit mr-2"></i>
                    {{ __('profile.personal_information') }}
                </h2>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        {{ __('profile.full_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror" 
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror" 
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Display (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                        {{ __('profile.role') }}
                    </label>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center text-sm font-semibold text-purple-800 dark:text-purple-300">
                                <i class="fas fa-user-shield mr-2"></i>
                                {{ __('users.administrator') }}
                            </span>
                        @else
                            <span class="inline-flex items-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                                <i class="fas fa-user mr-2"></i>
                                {{ __('users.user') }}
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('profile.role_cannot_change') }}
                    </p>
                </div>

                <!-- Save Button -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        type="submit" 
                        class="btn-primary px-3 py-1.5 sm:px-6 sm:py-3 rounded-lg text-sm sm:text-base text-white font-medium shadow-md hover:shadow-lg transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px]">
                        <i class="fas fa-save"></i>
                        {{ __('profile.save_changes') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-700 dark:to-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <i class="fas fa-lock mr-2"></i>
                    {{ __('profile.change_password') }}
                </h2>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-key text-gray-400 mr-1"></i>
                        {{ __('profile.current_password') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('current_password') border-red-500 @enderror" 
                        placeholder="{{ __('profile.current_password_placeholder') }}"
                    >
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        {{ __('profile.new_password') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="new_password" 
                        id="new_password" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('new_password') border-red-500 @enderror" 
                        placeholder="{{ __('profile.new_password_placeholder') }}"
                    >
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        {{ __('profile.confirm_new_password') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="new_password_confirmation" 
                        id="new_password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" 
                        placeholder="{{ __('profile.confirm_password_placeholder') }}"
                    >
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-1">{{ __('profile.security_tips_title') }}</h3>
                            <div class="text-sm text-blue-700 dark:text-blue-400">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>{{ __('profile.security_tip_1') }}</li>
                                    <li>{{ __('profile.security_tip_2') }}</li>
                                    <li>{{ __('profile.security_tip_3') }}</li>
                                    <li>{{ __('profile.security_tip_4') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        type="submit" 
                        class="btn-primary px-3 py-1.5 sm:px-6 sm:py-3 rounded-lg text-sm sm:text-base text-white font-medium shadow-md hover:shadow-lg transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px]">
                        <i class="fas fa-key"></i>
                        {{ __('profile.change_password') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Info -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                {{ __('profile.account_info') }}
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('profile.account_created') }}</span>
                    {{ $user->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('profile.last_updated') }}</span>
                    {{ $user->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePhotoPreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-32 h-32 rounded-full object-cover border-4 border-purple-100">`;
            }
            reader.readAsDataURL(file);
        }
    }
    
    // Modal de confirmação para deletar foto
    document.querySelectorAll('.delete-photo-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-photo-form');
            
            if (window.showConfirmModal) {
                window.showConfirmModal({
                    title: '{{ __('profile.delete_photo') }}',
                    message: '{{ __('profile.confirm_delete_photo') }}',
                    warning: '{{ __('companies.delete_warning') }}',
                    confirmText: '{{ __('users.delete') }}',
                    cancelText: '{{ __('companies.cancel') }}',
                    confirmColor: 'red',
                    onConfirm: () => {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection

