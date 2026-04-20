@extends('layouts.app')
@php($title = __('ui.auth.register_title'))

@section('content')
    <div style="max-width: 640px; width: 100%; margin: 0 auto; display: grid; gap: 18px;">
        <div class="section-header" style="text-align: center; justify-items: center;">
            <div class="eyebrow">{{ __('ui.auth.register_title') }}</div>
            <h1>{{ __('ui.auth.register_heading') }}</h1>
            <p>{{ __('ui.auth.register_intro') }}</p>
        </div>

        <div class="panel">
            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div>
                    <label for="name">{{ __('ui.auth.name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="alert" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email">{{ __('ui.auth.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="alert" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password">{{ __('ui.auth.password') }}</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')
                        <p class="alert" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation">{{ __('ui.auth.password_confirmation') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <button type="submit">{{ __('ui.auth.register_submit') }}</button>

                <p class="muted">{{ __('ui.auth.has_account') }} <a href="{{ route('login') }}">{{ __('ui.nav.login') }}</a></p>
            </form>
        </div>
    </div>
@endsection
