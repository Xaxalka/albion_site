@extends('layouts.app')
@php($title = __('ui.auth.verify_title'))

@section('content')
    <div style="max-width: 640px; width: 100%; margin: 0 auto; display: grid; gap: 18px;">
        <div class="section-header" style="text-align: center; justify-items: center;">
            <div class="eyebrow">{{ __('ui.auth.register_title') }}</div>
            <h1>{{ __('ui.auth.verify_heading') }}</h1>
            <p>{{ __('ui.auth.verify_intro', ['email' => $email]) }}</p>
        </div>

        <div class="panel">
            <form method="POST" action="{{ route('register.verify.store') }}">
                @csrf

                <div>
                    <label for="code">{{ __('ui.auth.verification_code') }}</label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        value="{{ old('code') }}"
                        required
                    >
                    @error('code')
                        <p class="alert" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit">{{ __('ui.auth.verify_submit') }}</button>
            </form>
        </div>
    </div>
@endsection
