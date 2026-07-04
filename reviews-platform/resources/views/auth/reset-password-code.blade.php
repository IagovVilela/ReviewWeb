@extends('layouts.auth')

@section('title', 'Verify code')

@section('content')
    <div>
        <h2 class="editorial-display text-3xl tracking-tight">Verify code</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Enter the 6-digit code sent to your email</p>
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

    <form method="POST" action="{{ url(route('password.verify-code', [], false)) }}" id="codeForm" class="mt-8">
        @csrf
        <div class="flex justify-between gap-2">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" class="code-input editorial-input !w-12 !px-0 text-center text-lg font-medium" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
            @endfor
        </div>
        <input type="hidden" name="code" id="fullCode">
        <button type="submit" class="editorial-btn-primary mt-6 w-full">Verify code</button>
    </form>

    <p class="mt-6 text-center text-sm">
        <a href="{{ route('password.forgot') }}" class="text-gray-600 hover:text-primary-600">Back</a>
    </p>
@endsection

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.code-input');
    const form = document.getElementById('codeForm');
    const fullCodeInput = document.getElementById('fullCode');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = e.clipboardData.getData('text').slice(0, 6);
            paste.split('').forEach((char, i) => {
                if (inputs[i] && /[0-9]/.test(char)) inputs[i].value = char;
            });
            inputs[Math.min(paste.length, 5)].focus();
        });
    });

    form.addEventListener('submit', (e) => {
        const code = Array.from(inputs).map((i) => i.value).join('');
        fullCodeInput.value = code;
        if (code.length !== 6) {
            e.preventDefault();
            alert('Please enter the full 6-digit code.');
        }
    });

    inputs[0]?.focus();
</script>
@endpush
