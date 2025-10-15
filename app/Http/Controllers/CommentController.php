<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        // Rediriger vers la bonne route selon le type de projet
        if ($task->project->company_id) {
            return redirect()->route('entreprise.tasks.show', $task->id)->with('success', 'Comment added successfully.');
        } else {
            return redirect()->route('tasks.show', $task->id)->with('success', 'Comment added successfully.');
        }
    }

    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // Vérifier l'autorisation : seul l'auteur du commentaire peut le supprimer
        if ($comment->user_id !== $user->id && !$user->is_admin()) {
            abort(403, 'Vous ne pouvez supprimer que vos propres commentaires.');
        }

        $taskId = $comment->task_id;
        $comment->delete();

        // Rediriger vers la bonne route selon le type de projet
        if ($comment->task->project->company_id) {
            return redirect()->route('entreprise.tasks.show', $taskId)->with('success', 'Commentaire supprimé avec succès.');
        } else {
            return redirect()->route('tasks.show', $taskId)->with('success', 'Commentaire supprimé avec succès.');
        }
    }
}