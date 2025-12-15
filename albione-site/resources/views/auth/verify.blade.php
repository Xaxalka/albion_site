@extends('layouts.app')

@section('content')
    <div class="section-header">
        <div class="eyebrow">Подтверждение email</div>
        <h1>Введите код из письма</h1>
        <p>Мы отправили шестизначный код на указанный вами адрес. Введите его ниже.</p>
    </div>

    <div class="panel" style="max-width: 540px;">
        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf

            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required>
                @error('email')
                    <p class="alert" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="code">Код из письма</label>
                <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" value="{{ old('code') }}" required>
                @error('code')
                    <p class="alert" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Подтвердить</button>
        </form>

        <form method="POST" action="{{ route('verification.resend') }}" style="margin-top: 12px;">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $email) }}">
            <button type="submit">Отправить код ещё раз</button>
        </form>
    </div>
@endsection
