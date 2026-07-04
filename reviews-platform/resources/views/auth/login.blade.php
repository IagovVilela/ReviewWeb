@extends('layouts.auth')

@section('title', __('auth.title'))

@section('content')
    <div>
        <h2 class="editorial-display text-3xl tracking-tight">{{ __('auth.welcome_back') }}</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('auth.login_subtitle') }}</p>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login', [], false) }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="editorial-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="editorial-input" placeholder="{{ __('auth.email_placeholder') }}">
        </div>
        <div>
            <label class="editorial-label" for="password">{{ __('auth.password') }}</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" class="editorial-input" placeholder="{{ __('auth.password_placeholder') }}">
        </div>
        <button type="submit" class="editorial-btn-primary w-full">{{ __('auth.login_button') }}</button>
    </form>

    <div class="mt-6 space-y-3 text-center text-sm">
        <a href="{{ route('password.forgot') }}" class="text-gray-600 dark:text-gray-300 transition hover:text-primary-600">{{ __('app.forgot') ?? 'Forgot password?' }}</a>
        <p>
            <span class="text-gray-600 dark:text-gray-300">{{ __('auth.no_account_yet') }}</span>
            <a href="{{ route('register') }}" class="ml-1 font-medium text-primary-600 underline-offset-4 hover:underline">{{ __('auth.register_title') }}</a>
        </p>
    </div>
@endsection
