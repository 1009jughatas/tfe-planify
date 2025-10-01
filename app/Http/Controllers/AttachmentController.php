<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Upload a file to a project or task.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur est premium
        if (!$user->is_premium && !$user->is_admin()) {
            return back()->with('error', 'Le stockage et partage de fichiers est une fonctionnalité premium.');
        }

        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'attachable_type' => 'required|in:project,task',
            'attachable_id' => 'required|integer',
        ]);

        // Vérifier l'autorisation sur le projet/tâche
        if ($request->attachable_type === 'project') {
            $attachable = Project::findOrFail($request->attachable_id);
            if (!$user->can('uploadFiles', $attachable)) {
                abort(403, 'Vous n\'avez pas l\'autorisation d\'uploader des fichiers sur ce projet.');
            }
        } else {
            $attachable = Task::findOrFail($request->attachable_id);
            if (!$user->can('view', $attachable)) {
                abort(403, 'Vous n\'avez pas l\'autorisation d\'uploader des fichiers sur cette tâche.');
            }
        }

        $file = $request->file('file');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('attachments', $filename, 'public');

        $attachment = Attachment::create([
            'attachable_id' => $request->attachable_id,
            'attachable_type' => $request->attachable_type === 'project' ? Project::class : Task::class,
            'user_id' => $user->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
        ]);

        return back()->with('success', 'Fichier uploadé avec succès.');
    }

    /**
     * Download an attachment.
     */
    public function download(Attachment $attachment)
    {
        $user = auth()->user();

        // Vérifier l'autorisation
        if ($attachment->attachable_type === Project::class) {
            if (!$user->can('view', $attachment->attachable)) {
                abort(403, 'Accès non autorisé.');
            }
        } else {
            if (!$user->can('view', $attachment->attachable)) {
                abort(403, 'Accès non autorisé.');
            }
        }

        return Storage::disk('public')->download($attachment->path, $attachment->original_filename);
    }

    /**
     * Delete an attachment.
     */
    public function destroy(Attachment $attachment)
    {
        $user = auth()->user();

        // Seul l'auteur du fichier ou un admin peut le supprimer
        if ($attachment->user_id !== $user->id && !$user->is_admin()) {
            abort(403, 'Seul l\'auteur du fichier peut le supprimer.');
        }

        // Supprimer le fichier du stockage
        Storage::disk('public')->delete($attachment->path);

        // Supprimer l'entrée de la base de données
        $attachment->delete();

        return back()->with('success', 'Fichier supprimé avec succès.');
    }
}
