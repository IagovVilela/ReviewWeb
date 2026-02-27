@extends('layouts.admin')

@section('title', __('users.edit_title') . ' - ' . __('app.name'))
@section('page-title', __('users.edit_title'))
@section('page-description', __('users.edit_description'))

@section('content')
<div class="fade-in">
    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('users.index') }}" class="hover:text-purple-600 transition-colors">
                        <i class="fas fa-users"></i> {{ __('users.title') }}
                    </a>
                </li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li class="text-purple-600 font-medium">{{ __('users.edit_user') }} {{ $targetUser->name }}</li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-lg">
                                {{ strtoupper(substr($targetUser->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">{{ $targetUser->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $targetUser->email }}</p>
                        </div>
                    </div>
                    @if($targetUser->role === 'proprietario')
                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700">
                            <i class="fas fa-crown mr-1.5"></i>
                            {{ __('users.owner') }}
                        </span>
                    @elseif($targetUser->role === 'admin')
                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            <i class="fas fa-user-shield mr-1.5"></i>
                            {{ __('users.administrator') }}
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            <i class="fas fa-user mr-1.5"></i>
                            {{ __('users.user') }}
                        </span>
                    @endif
                </div>
            </div>

            <form id="userUpdateForm" action="{{ route('users.update', $targetUser->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        {{ __('users.full_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $targetUser->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror" 
                        placeholder="{{ __('users.full_name_placeholder') }}"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $targetUser->email) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror" 
                        placeholder="{{ __('users.email_placeholder') }}"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                        {{ __('users.role') }} <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="role" 
                        id="role" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('role') border-red-500 @enderror"
                        @if(Auth::user()->role === 'admin') disabled @endif
                        required
                    >
                        <option value="">{{ __('users.select_role') }}</option>
                        @if(Auth::user()->role === 'proprietario')
                            <option value="proprietario" {{ old('role', $targetUser->role) === 'proprietario' ? 'selected' : '' }}>{{ __('users.role_owner_full') }}</option>
                            <option value="admin" {{ old('role', $targetUser->role) === 'admin' ? 'selected' : '' }}>{{ __('users.role_admin_full') }}</option>
                        @endif
                        <option value="user" {{ old('role', $targetUser->role) === 'user' ? 'selected' : '' }}>{{ __('users.role_user_limited') }}</option>
                    </select>
                    @if(Auth::user()->role === 'admin')
                        <input type="hidden" name="role" value="{{ $targetUser->role }}">
                        <p class="mt-1 text-xs text-yellow-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Como administrador, você não pode alterar a função deste usuário.
                        </p>
                    @elseif(Auth::user()->role === 'proprietario')
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ __('users.role_owner_info') }}
                        </p>
                    @endif
                    @error('role')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Section -->
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-md font-medium text-gray-800 mb-4">
                        <i class="fas fa-key text-gray-400 mr-2"></i>
                        {{ __('users.change_password') }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        {{ __('users.password_blank_info') }}
                    </p>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            {{ __('users.new_password') }}
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror" 
                            placeholder="{{ __('users.password_placeholder') }}"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            {{ __('users.confirm_new_password') }}
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" 
                            placeholder="{{ __('users.password_confirm_placeholder') }}"
                        >
                    </div>
                </div>

                <!-- Warning for Self-Edit -->
                @if($targetUser->id === Auth::id())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 mb-1">{{ __('users.warning_self_edit_title') }}</h3>
                            <p class="text-sm text-yellow-700">
                                {{ __('users.warning_self_edit_message') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- User Info -->
                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="font-medium text-gray-700">{{ __('users.created_at') }}</span>
                            {{ $targetUser->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">{{ __('users.last_updated') }}</span>
                            {{ $targetUser->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

            </form>

            <!-- Form Actions (fora do form para evitar forms aninhados) -->
            <div class="p-6 pt-0">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 sm:gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}"
                       class="btn-secondary px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-lg text-sm sm:text-base text-white font-medium shadow-sm hover:shadow-md transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px] justify-center sm:justify-start">
                        <i class="fas fa-arrow-left text-xs sm:text-sm"></i>
                        <span class="hidden sm:inline">{{ __('users.back') }}</span>
                        <span class="sm:hidden">{{ __('app.back') }}</span>
                    </a>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                        @if($targetUser->id !== Auth::id())
                        <form action="{{ route('users.destroy', $targetUser->id) }}"
                              method="POST"
                              class="inline-block delete-user-form w-full sm:w-auto"
                              data-user-name="{{ $targetUser->name }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="button"
                                class="px-3 py-1.5 sm:px-6 sm:py-2.5 bg-red-600 rounded-lg text-sm sm:text-base text-white font-medium shadow-sm hover:shadow-md hover:bg-red-700 transition-all inline-flex items-center gap-1.5 sm:gap-2 delete-user-btn min-h-[36px] sm:min-h-[44px] justify-center w-full sm:w-auto">
                                <i class="fas fa-trash text-xs sm:text-sm"></i>
                                <span class="hidden sm:inline">{{ __('users.delete') }}</span>
                                <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('app.delete')) }}</span>
                            </button>
                        </form>
                        @endif
                        <button
                            type="submit"
                            form="userUpdateForm"
                            class="btn-primary px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-lg text-sm sm:text-base text-white font-medium shadow-md hover:shadow-lg transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px] justify-center w-full sm:w-auto">
                            <i class="fas fa-save text-xs sm:text-sm"></i>
                            <span class="hidden sm:inline">{{ __('users.save_changes') }}</span>
                            <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('app.save')) }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Translations for JavaScript
    const translations = {
        pt_BR: {
            saving: '{{ __('users.saving') }}',
            password_mismatch: '{{ __('users.password_mismatch') }}'
        },
        en_US: {
            saving: '{{ __('users.saving') }}',
            password_mismatch: '{{ __('users.password_mismatch') }}'
        }
    };
    
    const currentLang = '{{ app()->getLocale() }}';
    const t = translations[currentLang] || translations.pt_BR;
    
    // Form validation feedback
    document.getElementById('userUpdateForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + t.saving;
        
        // Re-enable if there's an error
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 5000);
    });

    // Password confirmation validation
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    passwordInput.addEventListener('input', function() {
        if (this.value) {
            passwordConfirmInput.required = true;
        } else {
            passwordConfirmInput.required = false;
        }
    });

    passwordConfirmInput.addEventListener('input', function() {
        if (passwordInput.value && this.value !== passwordInput.value) {
            this.setCustomValidity(t.password_mismatch);
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Modal de confirmação para deletar usuário
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-user-form');
            const userName = form.getAttribute('data-user-name');
            
            if (window.showConfirmModal) {
                window.showConfirmModal({
                    title: '{{ __('users.delete') }}',
                    message: '{{ __('users.confirm_delete_permanent') }}',
                    warning: '{{ __('users.delete_warning') }}',
                    confirmText: '{{ __('users.delete') }}',
                    cancelText: '{{ __('companies.cancel', ['default' => 'Cancelar']) }}',
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

