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

        return redirect()->route('tasks.show', $task->id)->with('success', 'Comment added successfully.');
    }
}