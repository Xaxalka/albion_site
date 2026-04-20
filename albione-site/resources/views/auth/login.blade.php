@extends('layouts.app')
@php($title = __('ui.auth.login_title'))

@section('content')
    <div style="max-width: 480px; width: 100%; margin: 0 auto; display: grid; gap: 18px;">
        <div class="section-header" style="text-align: center; justify-items: center;">
            <div class="eyebrow">{{ __('ui.auth.login_title') }}</div>
            <h1>{{ __('ui.auth.login_heading') }}</h1>
            <p>{{ __('ui.auth.login_intro') }}</p>
        </div>

        <div class="panel">
            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div>
                    <label for="login">{{ __('ui.auth.login_field') }}</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus>
                    @error('login')
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

                <button type="submit">{{ __('ui.nav.login') }}</button>
            </form>
        </div>
    </div>
@endsection
