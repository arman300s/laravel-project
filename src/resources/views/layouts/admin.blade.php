<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная панель</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-container">
    <aside class="admin-sidebar">
        <h1 class="admin-title">Библиотека</h1>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                <i class="fas fa-tachometer-alt admin-nav-icon"></i> Панель управления
            </a>
        </nav>
    </aside>

    <main class="admin-main">
        @yield('content')
    </main>
</div>

</body>
</html>
