<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class RoleMiddleware
{
    public function handle(Request $request, ?string $roles = null): void
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
        }

        if (empty($roles)) {
            return;
        }

        $allowedRoles = array_filter(array_map('trim', explode(',', $roles)));
        $userRole = Auth::user()->role ?? '';

        if (!in_array($userRole, $allowedRoles, true)) {
            app()->route->redirect('/');
        }
    }
}


