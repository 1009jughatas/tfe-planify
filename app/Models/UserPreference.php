<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dark_mode',
        'theme_color',
        'language',
        'email_notifications',
        'task_reminders',
        'reminder_hours_before',
        'date_format',
        'timezone',
    ];

    protected $casts = [
        'dark_mode' => 'boolean',
        'email_notifications' => 'boolean',
        'task_reminders' => 'boolean',
        'reminder_hours_before' => 'integer',
    ];

    /**
     * Get the user that owns the preferences.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Available theme colors.
     */
    public static function availableColors()
    {
        return [
            '#3b82f6' => 'Bleu',
            '#10b981' => 'Vert',
            '#f59e0b' => 'Orange',
            '#ef4444' => 'Rouge',
            '#8b5cf6' => 'Violet',
            '#ec4899' => 'Rose',
            '#14b8a6' => 'Turquoise',
            '#6366f1' => 'Indigo',
        ];
    }

    /**
     * Available languages.
     */
    public static function availableLanguages()
    {
        return [
            'fr' => 'Français',
            'en' => 'English',
            'nl' => 'Nederlands',
        ];
    }
}
