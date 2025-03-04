@extends('layouts.admin')

@section('content')
    <h1 class="admin-page-title">Панель управления</h1>

    <div class="admin-dashboard-grid">
        <div class="admin-dashboard-item">
            <h2 class="admin-dashboard-item-title">Общее количество книг</h2>
            <p class="admin-dashboard-item-value">{{ $totalBooks }}</p>
        </div>

        <div class="admin-dashboard-item">
            <h2 class="admin-dashboard-item-title">Общее количество пользователей</h2>
            <p class="admin-dashboard-item-value">{{ $totalUsers }}</p>
        </div>

    </div>
@endsection
