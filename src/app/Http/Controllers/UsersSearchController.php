<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsersSearchController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the user search page (admin version).
     */
    public function adminIndex(Request $request): View
    {

        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->when($request->role, function($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.search-admin', [
            'users' => $users,
            'search' => $request->search,
            'role' => $request->role
        ]);
    }

    /**
     * Display the user search page (regular user version).
     */
    public function userIndex(Request $request): View
    {
        $this->authorize('search', User::class);

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10);

        return view('user.users.search-user', [
            'users' => $users,
            'search' => $request->search
        ]);
    }
}
