@extends('layouts.app')

@section('content')
    <div class="section-header">
        <div class="eyebrow">Регистрация</div>
        <h1>Создать учетную запись</h1>
        <p>Заполните форму, чтобы получать доступ к возможностям сайта.</p>
    </div>

    <div class="panel" style="max-width: 640px;">
        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div>
                <label for="name">Имя</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')
                    <p class="alert" role="alert">{{ $message }}</p>
                @enderror
            </div>

            @if($supportsUsername ?? false)
                <div>
                    <label for="username">Логин</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required>
                    @error('username')
                        <p class="alert" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="alert" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" required>
                @error('password')
                    <p class="alert" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation">Подтверждение пароля</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <button type="submit">Зарегистрироваться</button>

            <p class="muted">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
        </form>
    </div>
@endsection
