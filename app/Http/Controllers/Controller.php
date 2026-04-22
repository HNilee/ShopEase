<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function ensureOwner()
    {
        if (!Auth::check() || !Auth::user()->isOwner()) {
            abort(403, 'This action is only for owners.');
        }
    }

    protected function ensureAdminOrOwner()
    {
        if (!Auth::check() || !(Auth::user()->isAdmin() || Auth::user()->isOwner())) {
            abort(403, 'This action is only for admins or owners.');
        }
    }

    protected function ensureSeller()
    {
        if (!Auth::check() || !Auth::user()->isSeller()) {
            abort(403, 'This action is only for sellers.');
        }
    }

    protected function ensureCanManageUsers()
    {
        if (!Auth::check() || !Auth::user()->canManageUsers()) {
            abort(403, 'You do not have permission to manage users.');
        }
    }

    protected function ensureCanManageProducts()
    {
        if (!Auth::check() || !Auth::user()->canManageProducts()) {
            abort(403, 'You do not have permission to manage products.');
        }
    }
}
