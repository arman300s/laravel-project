<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|max:5120'
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Жаңа аватарды жүктеу
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = basename($avatarPath);
        $user->save();

        return back()->with('success', 'Avatar updated successfully!');
    }
    /**
     * Удаление аватара пользователя.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            // Удаляем файл с диска
            $avatarPath = 'avatars/' . $user->avatar;
            if (Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            $user->avatar = null;
            $user->save();

            return back()->with('success', 'Avatar deleted successfully!');
        }

        return back()->with('error', 'No avatar to delete.');
    }
}
