<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display users list with filters.
     */
    public function index(Request $request): View
    {
        $query = User::with('organization');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        $validated = $request->validate([
            'role' => ['required', 'in:admin,organizer,participant'],
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);

        // If updated to organizer and has no organization, create placeholder
        if ($validated['role'] === 'organizer' && ! $user->organization) {
            Organization::create([
                'user_id' => $user->id,
                'name' => 'Organisasi '.$user->name,
                'description' => 'Organisasi baru dibentuk.',
            ]);
        }

        AuditLogger::log('user.role_changed', 'User', $user->id, [
            'old_role' => $oldRole,
            'new_role' => $validated['role'],
        ]);

        return back()->with('success', 'Peran pengguna '.$user->name.' berhasil diubah menjadi '.strtoupper($validated['role']).'.');
    }
}
