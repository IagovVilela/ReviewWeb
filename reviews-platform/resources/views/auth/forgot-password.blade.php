@extends('layouts.auth')

@section('title', 'Recover password')

@section('content')
    <div>
        <h2 class="editorial-display text-3xl tracking-tight">Recover password</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Enter your email to receive a recovery code</p>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url(route('password.send-code', [], false)) }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="editorial-label" for="email">{{ __('auth.email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="editorial-input" placeholder="{{ __('auth.email_placeholder') }}">
        </div>
        <button type="submit" class="editorial-btn-primary w-full">Send code</button>
    </form>

    <p class="mt-6 text-center text-xs text-gray-500">
        If the email exists in our system, you will receive a recovery code.
    </p>
@endsection
