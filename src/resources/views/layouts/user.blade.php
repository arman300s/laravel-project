<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Библиотека</title>
    <link rel="stylesheet" href="/css/user.css">
</head>
<body class="user-body">

<header class="user-header">
    <nav class="user-nav">
        <a href="{{ route('user.dashboard') }}" class="user-logo">Библиотека</a>
    </nav>
</header>

<main class="user-main">
    @yield('content')
</main>

<footer class="user-footer">
    &copy; {{ date('Y') }} Библиотека
</footer>

</body>
</html>
