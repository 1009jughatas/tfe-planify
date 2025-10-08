<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author_id',
        'company_id',
        'start_date',
        'end_date',
        'status',
        'priority',
        'deadline',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'deadline' => 'date',
        'status' => 'string',
        'priority' => 'string',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get project completion percentage.
     */
    public function getCompletionPercentageAttribute()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }
        $completedTasks = $this->tasks()->where('status', 'completed')->count();
        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    /**
     * Get average task completion time in days.
     */
    public function getAverageCompletionTimeAttribute()
    {
        $completedTasks = $this->tasks()->where('status', 'completed')->get();
        if ($completedTasks->isEmpty()) {
            return 0;
        }
        
        $totalDays = 0;
        foreach ($completedTasks as $task) {
            if ($task->created_at && $task->updated_at) {
                $totalDays += $task->created_at->diffInDays($task->updated_at);
            }
        }
        
        return round($totalDays / $completedTasks->count(), 2);
    }
}