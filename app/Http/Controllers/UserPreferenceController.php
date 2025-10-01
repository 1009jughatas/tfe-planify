<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    /**
     * Show the preferences form.
     */
    public function edit()
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur est premium
        if (!$user->is_premium && !$user->is_admin()) {
            return redirect()->route('premium.show')->with('info', 'La personnalisation de l\'interface est une fonctionnalité premium.');
        }

        $preferences = $user->getPreferences();
        $availableColors = UserPreference::availableColors();
        $availableLanguages = UserPreference::availableLanguages();

        return view('preferences.edit', compact('preferences', 'availableColors', 'availableLanguages'));
    }

    /**
     * Update the user's preferences.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur est premium
        if (!$user->is_premium && !$user->is_admin()) {
            return redirect()->route('premium.show')->with('info', 'La personnalisation de l\'interface est une fonctionnalité premium.');
        }

        $request->validate([
            'dark_mode' => 'boolean',
            'theme_color' => 'string|max:7',
            'language' => 'string|in:fr,en,nl',
            'email_notifications' => 'boolean',
            'task_reminders' => 'boolean',
            'reminder_hours_before' => 'integer|min:1|max:168',
        ]);

        $preferences = $user->getPreferences();
        $preferences->update($request->only([
            'dark_mode',
            'theme_color',
            'language',
            'email_notifications',
            'task_reminders',
            'reminder_hours_before',
        ]));

        return redirect()->back()->with('success', 'Préférences mises à jour avec succès.');
    }
}
