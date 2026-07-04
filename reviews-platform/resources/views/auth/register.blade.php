@extends('layouts.auth')

@section('title', __('auth.register_title'))

@section('content')
    <div>
        <h2 class="editorial-display text-3xl tracking-tight">{{ __('auth.register_title') }}</h2>
        <p class="mt-2 text-sm text-ink-muted">{{ __('auth.register_subtitle') }}</p>
    </div>

    @if($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="editorial-label" for="name">{{ __('auth.name') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" class="editorial-input" placeholder="{{ __('auth.name_placeholder') }}">
        </div>
        <div>
            <label class="editorial-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="editorial-input" placeholder="{{ __('auth.email_placeholder') }}">
        </div>
        <div>
            <label class="editorial-label" for="password">{{ __('auth.password') }}</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" class="editorial-input" placeholder="{{ __('auth.password_placeholder') }}">
        </div>
        <div>
            <label class="editorial-label" for="password_confirmation">{{ __('auth.password') }}</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="editorial-input" placeholder="{{ __('auth.password_placeholder') }}">
        </div>
        <button type="submit" class="editorial-btn-primary w-full">{{ __('auth.register_button') }}</button>
    </form>

    <p class="mt-6 text-center text-sm text-ink-muted">
        {{ __('auth.already_have_account') }}
        <a href="{{ route('login') }}" class="font-medium text-ink underline-offset-4 hover:underline">{{ __('auth.login_here') }}</a>
    </p>
@endsection
