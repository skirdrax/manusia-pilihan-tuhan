<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    /**
     * Berpindah mode peran secara langsung (Admin / Kurir / User)
     */
    public function switch(string $role): RedirectResponse
    {
        if (!in_array($role, ['admin', 'kurir', 'user'])) {
            return back()->with('error', 'Peran tidak valid.');
        }

        $targetUser = User::where('role', $role)->first();

        if ($targetUser) {
            Auth::login($targetUser);
            session()->regenerate();

            $roleName = ucfirst($role);
            return redirect()->route('dashboard')->with('success', "Mode berhasil diubah ke {$roleName} ({$targetUser->name}).");
        }

        return back()->with('error', "Akun dengan role {$role} tidak ditemukan.");
    }
}
