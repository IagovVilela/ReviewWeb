@extends('layouts.admin')

@section('title', __('users.create_title'))
@section('page-title', __('users.create_title'))
@section('page-description', __('users.create_description'))

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
                <li class="text-purple-600 font-medium">{{ __('users.create_new') }}</li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('users.form_title') }}</h2>
                        <p class="text-sm text-gray-600">{{ __('users.form_description') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

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
                        value="{{ old('name') }}"
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
                        {{ __('app.email') }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
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
                        required
                        @if(Auth::user()->role === 'admin') disabled @endif
                    >
                        <option value="">{{ __('users.select_role') }}</option>
                        @if(Auth::user()->role === 'proprietario')
                            <option value="proprietario" {{ old('role') === 'proprietario' ? 'selected' : '' }}>{{ __('users.role_owner_full') }}</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('users.role_admin_full') }}</option>
                        @endif
                        <option value="user" {{ old('role') === 'user' || Auth::user()->role === 'admin' ? 'selected' : '' }}>{{ __('users.role_user_limited') }}</option>
                    </select>
                    @if(Auth::user()->role === 'admin')
                        <input type="hidden" name="role" value="user">
                        <p class="mt-1 text-xs text-yellow-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ __('users.admin_only_message') }}
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

                <!-- Require payment (slider) -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <label for="payment_required" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('users.payment_required') }}
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('users.payment_required_help') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="payment_required" value="0">
                        <input type="checkbox" name="payment_required" id="payment_required" value="1" {{ old('payment_required', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <!-- Send welcome email (slider) -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <label for="send_welcome_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('users.send_welcome_email') }}
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('users.send_welcome_email_help') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="send_welcome_email" value="0">
                        <input type="checkbox" name="send_welcome_email" id="send_welcome_email" value="1" {{ old('send_welcome_email') ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <!-- Password -->
                <div id="password-fields">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        {{ __('app.password') }} <span id="password-required-asterisk" class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror" 
                        placeholder="{{ __('users.create_password_placeholder') }}"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div id="password-confirmation-field">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        {{ __('users.confirm_new_password') }} <span id="password-confirm-asterisk" class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" 
                        placeholder="{{ __('users.password_confirm_placeholder') }}"
                    >
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 mb-1">{{ __('users.important_info_title') }}</h3>
                            <div class="text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>{{ __('users.important_info_password') }}</li>
                                    <li>{{ __('users.important_info_email') }}</li>
                                    <li>{{ __('users.important_info_admins_only') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" 
                       class="btn-secondary px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-lg text-sm sm:text-base text-white font-medium shadow-sm hover:shadow-md transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px] justify-center sm:justify-start">
                        <i class="fas fa-times text-xs sm:text-sm"></i>
                        <span class="hidden sm:inline">{{ __('app.cancel') }}</span>
                        <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('app.cancel')) }}</span>
                    </a>
                    <button 
                        type="submit" 
                        class="btn-primary px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-lg text-sm sm:text-base text-white font-medium shadow-md hover:shadow-lg transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px] justify-center w-full sm:w-auto">
                        <i class="fas fa-save text-xs sm:text-sm"></i>
                        <span class="hidden sm:inline">{{ __('users.create_user') }}</span>
                        <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('companies.create')) }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Translations
    const translations = {
        creating: '{{ __('users.creating') }}',
        passwordMismatch: '{{ __('users.password_mismatch') }}'
    };

    // Form validation feedback
    document.querySelector('form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + translations.creating;
    });

    // Toggle password required when "send welcome email" is checked
    const sendWelcomeCheckbox = document.getElementById('send_welcome_email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    function updatePasswordRequired() {
        const optional = sendWelcomeCheckbox && sendWelcomeCheckbox.checked;
        if (passwordInput) {
            passwordInput.required = !optional;
            passwordInput.placeholder = optional ? '{{ __("users.send_welcome_email_help") }}' : '{{ __("users.create_password_placeholder") }}';
        }
        if (passwordConfirmInput) passwordConfirmInput.required = !optional;
        const asterisks = document.querySelectorAll('#password-required-asterisk, #password-confirm-asterisk');
        asterisks.forEach(el => { if (el) el.style.visibility = optional ? 'hidden' : 'visible'; });
    }
    if (sendWelcomeCheckbox) {
        sendWelcomeCheckbox.addEventListener('change', updatePasswordRequired);
        updatePasswordRequired();
    }

    passwordInput.addEventListener('input', function() {
        const strength = getPasswordStrength(this.value);
    });
    passwordConfirmInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
            this.setCustomValidity(translations.passwordMismatch);
        } else {
            this.setCustomValidity('');
        }
    });

    function getPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 6) strength++;
        if (password.length >= 10) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z\d]/.test(password)) strength++;
        return strength;
    }
</script>
@endsection

