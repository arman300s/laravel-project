@extends('layouts.user')

@section('content')
    <div class="welcome-container">
        <h1 class="welcome-title">Добро пожаловать в библиотеку</h1>
        <p class="welcome-text">Наслаждайтесь чтением и открывайте новые знания.</p>
        <a href="{{ route('login') }}" class="welcome-button">Войти</a>
    </div>
@endsection
