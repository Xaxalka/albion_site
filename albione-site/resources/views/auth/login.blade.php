@extends('layouts.app')

@section('content')
    <div class="section-header">
        <div class="eyebrow">Авторизация</div>
        <h1>Вход в панель администратора</h1>
        <p>Используйте выданные учетные данные, чтобы управлять контентом.</p>
    </div>

    <div class="panel" style="max-width: 480px;">
        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div>
                <label for="login">Логин или email</label>
                <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus>
                @error('login')
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

            <button type="submit">Войти</button>
        </form>
    </div>
@endsection
