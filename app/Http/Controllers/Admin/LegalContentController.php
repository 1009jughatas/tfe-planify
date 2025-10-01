<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LegalContentController extends Controller
{
    /**
     * Show the legal content management page.
     */
    public function index()
    {
        $legalFiles = [
            'mentions_legales' => storage_path('app/legal/mentions_legales.txt'),
            'cgu' => storage_path('app/legal/cgu.txt'),
            'politique_confidentialite' => storage_path('app/legal/politique_confidentialite.txt'),
        ];

        $contents = [];
        foreach ($legalFiles as $key => $path) {
            if (File::exists($path)) {
                $contents[$key] = File::get($path);
            } else {
                $contents[$key] = '';
            }
        }

        return view('admin.legal.index', compact('contents'));
    }

    /**
     * Update legal content.
     */
    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|in:mentions_legales,cgu,politique_confidentialite',
            'content' => 'required|string',
        ]);

        $directory = storage_path('app/legal');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = $request->type . '.txt';
        $path = $directory . '/' . $filename;

        File::put($path, $request->content);

        return back()->with('success', 'Contenu légal mis à jour avec succès.');
    }

    /**
     * Display mentions légales.
     */
    public function showMentions()
    {
        $path = storage_path('app/legal/mentions_legales.txt');
        $content = File::exists($path) ? File::get($path) : 'Contenu non disponible.';

        return view('admin.legal.show', ['title' => 'Mentions Légales', 'content' => $content]);
    }

    /**
     * Display CGU.
     */
    public function showCGU()
    {
        $path = storage_path('app/legal/cgu.txt');
        $content = File::exists($path) ? File::get($path) : 'Contenu non disponible.';

        return view('admin.legal.show', ['title' => 'Conditions Générales d\'Utilisation', 'content' => $content]);
    }

    /**
     * Display politique de confidentialité.
     */
    public function showPrivacy()
    {
        $path = storage_path('app/legal/politique_confidentialite.txt');
        $content = File::exists($path) ? File::get($path) : 'Contenu non disponible.';

        return view('admin.legal.show', ['title' => 'Politique de Confidentialité', 'content' => $content]);
    }
}
