<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect authenticated users to the appropriate destination.
     * Admins go to the admin blog post management screen; all other users go to the home page.
     */
    public function __invoke(): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.blog.index');
        }

        return redirect()->route('home');
    }
}
