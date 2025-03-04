@extends('layouts.auth')

@section('content')
    <div class="auth-container">
        <div class="auth-form-container">
            <form class="auth-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="auth-form-group">
                    <label for="name" class="auth-label">Имя</label>
                    <input id="name" type="text" class="auth-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="email" class="auth-label">Email</label>
                    <input id="email" type="email" class="auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-label">Пароль</label>
                    <input id="password" type="password" class="auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password-confirm" class="auth-label">Подтвердите пароль</label>
                    <input id="password-confirm" type="password" class="auth-input" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="auth-form-group">
                    <button type="submit" class="auth-button">Зарегистрироваться</button>
                </div>
            </form>
        </div>
    </div>
@endsection
