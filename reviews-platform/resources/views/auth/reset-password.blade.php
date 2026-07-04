@extends('layouts.auth')

@section('title', 'Reset password')

@section('content')
    <div>
        <h2 class="editorial-display text-3xl tracking-tight">Reset password</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Choose a new password for your account</p>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url(route('password.reset', [], false)) }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="editorial-label" for="password">New password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" autofocus minlength="6" class="editorial-input" placeholder="Minimum 6 characters">
        </div>
        <div>
            <label class="editorial-label" for="password_confirmation">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" minlength="6" class="editorial-input" placeholder="Confirm new password">
        </div>
        <button type="submit" class="editorial-btn-primary w-full">Reset password</button>
    </form>
@endsection
