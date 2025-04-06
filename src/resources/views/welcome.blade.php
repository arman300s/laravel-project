<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Library Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
            }
        </style>
    @endif
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] flex justify-center items-center min-h-screen">
@if (Route::has('login'))
    <div class="flex flex-col items-center gap-4 p-8">
        <h1 class="text-4xl font-semibold">Library Management System</h1>
        <p class="text-lg text-gray-600">A simple and powerful tool to manage your books efficiently.</p>

        <div class="flex gap-4 mt-8">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="py-2 px-5 border rounded-md text-sm transition-all duration-150 bg-[#1b1b18] text-white border-[#1b1b18] hover:bg-[#2a2a26] hover:border-[#2a2a26]">
                        Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="py-2 px-5 border rounded-md text-sm transition-all duration-150 text-[#1b1b18] border-transparent hover:border-[#19140033]">
                        User Dashboard
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="py-2 px-5 border rounded-md text-sm transition-all duration-150 text-[#1b1b18] border-transparent hover:border-[#19140033]">
                        Log Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="py-2 px-5 border rounded-md text-sm transition-all duration-150 text-[#1b1b18] border-transparent hover:border-[#19140033]">
                    Log in
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="py-2 px-5 border rounded-md text-sm transition-all duration-150 text-[#1b1b18] border-[#19140033] hover:border-[#19140066]">
                        Register
                    </a>
                @endif
            @endauth
        </div>
    </div>
@endif
</body>
</html>
