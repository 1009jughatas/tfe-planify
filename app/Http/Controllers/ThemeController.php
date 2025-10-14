<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Toggle theme between light and dark mode
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->is_premium()) {
            return response()->json([
                'error' => 'Accès refusé. Cette fonctionnalité est réservée aux utilisateurs Premium.'
            ], 403);
        }

        $currentTheme = $request->cookie('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';

        $preferences = $user->getPreferences();
        $preferences->update(['theme' => $newTheme]);

        return response()->json([
            'success' => true,
            'theme' => $newTheme
        ])->withCookie(cookie('theme', $newTheme, 60 * 24 * 30)); // 30 jours
    }

    /**
     * Get current theme
     */
    public function get(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->is_premium()) {
            return response()->json([
                'theme' => 'light'
            ]);
        }

        $preferences = $user->getPreferences();
        $theme = $preferences->theme ?? $request->cookie('theme', 'light');

        return response()->json([
            'theme' => $theme
        ]);
    }

    /**
     * Set specific theme
     */
    public function set(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark'
        ]);

        $user = Auth::user();
        
        if (!$user || !$user->is_premium()) {
            return response()->json([
                'error' => 'Accès refusé. Cette fonctionnalité est réservée aux utilisateurs Premium.'
            ], 403);
        }

        $preferences = $user->getPreferences();
        $preferences->update(['theme' => $request->theme]);

        return response()->json([
            'success' => true,
            'theme' => $request->theme
        ])->withCookie(cookie('theme', $request->theme, 60 * 24 * 30)); // 30 jours
    }
}