<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Redirect users to authentication or their workspace.
     */
    public function index(): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard');
        }

        return redirect()->route('participant.tickets.index');
    }
}
