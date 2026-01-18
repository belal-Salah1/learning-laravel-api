<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $user = $request->user();

        // If user is authenticated via token (Sanctum), revoke the token(s)
        if ($user) {
            if (method_exists($user, 'currentAccessToken')) {
                $token = $user->currentAccessToken();
                if ($token) {
                    $token->delete();
                    return response()->noContent();
                }
            }

            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
                return response()->noContent();
            }
        }

        // Fallback to session-based logout
        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

        return response()->noContent();
    }
}
