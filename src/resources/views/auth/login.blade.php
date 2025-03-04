@extends('layouts.auth')

@section('content')
    <div class="auth-container">
        <div class="auth-form-container">
            <form class="auth-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="auth-form-group">
                    <label for="email" class="auth-label">Email</label>
                    <input id="email" type="email" class="auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-label">Пароль</label>
                    <input id="password" type="password" class="auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <button type="submit" class="auth-button">Войти</button>
                </div>

                <div class="auth-form-group">
                    <a href="{{ route('register') }}" class="auth-link">Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>
@endsection
