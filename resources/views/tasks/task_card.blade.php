<div class="task-card hover-lift group">
    <div class="task-card-header">
        <h5 class="task-title">{{ $task->title }}</h5>
        <div class="task-status-badge">
            @php
                $statusConfig = [
                    'todo' => ['class' => 'task-status-todo', 'icon' => 'fas fa-circle', 'text' => 'À faire'],
                    'pending' => ['class' => 'task-status-todo', 'icon' => 'fas fa-circle', 'text' => 'En attente'],
                    'in-progress' => ['class' => 'task-status-in-progress', 'icon' => 'fas fa-play-circle', 'text' => 'En cours'],
                    'blocked' => ['class' => 'task-status-blocked', 'icon' => 'fas fa-exclamation-circle', 'text' => 'Bloquée'],
                    'done' => ['class' => 'task-status-done', 'icon' => 'fas fa-check-circle', 'text' => 'Terminée'],
                    'completed' => ['class' => 'task-status-done', 'icon' => 'fas fa-check-circle', 'text' => 'Terminée']
                ];
                $config = $statusConfig[$task->status] ?? $statusConfig['todo'];
            @endphp
            <span class="task-status {{ $config['class'] }}" data-task-id="{{ $task->id }}" id="task-status-{{ $task->id }}">
                <i class="{{ $config['icon'] }} mr-1"></i>
                {{ $config['text'] }}
            </span>
        </div>
    </div>
    
    <div class="task-card-body">
        <p class="task-description">{{ \Illuminate\Support\Str::limit($task->description, 80, '...') ?: 'Aucune description' }}</p>
        
        <div class="task-meta">
            @if($task->assignedUser)
                <div class="task-meta-item">
                    <div class="w-6 h-6 bg-gradient-primary rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-medium">{{ substr($task->assignedUser->name, 0, 1) }}</span>
                    </div>
                    <span class="text-xs text-gray-600">{{ $task->assignedUser->name }}</span>
                </div>
            @endif
            
            @if($task->priority !== null)
                <div class="task-meta-item">
                    <span class="priority-indicator priority-{{ $task->priority }}">
                        @switch($task->priority)
                            @case(0) 🟢 @break
                            @case(1) 🟡 @break
                            @case(2) 🟠 @break
                            @case(3) 🔴 @break
                            @default
                                @if(is_string($task->priority))
                                    @switch($task->priority)
                                        @case('low') 🟢 @break
                                        @case('medium') 🟡 @break
                                        @case('high') 🟠 @break
                                        @case('urgent') 🔴 @break
                                        @default 🟡
                                    @endswitch
                                @else
                                    🟡
                                @endif
                        @endswitch
                    </span>
                    <span class="text-xs text-gray-600">
                        @if(is_numeric($task->priority))
                            @switch($task->priority)
                                @case(0) Basse @break
                                @case(1) Moyenne @break
                                @case(2) Haute @break
                                @case(3) Urgente @break
                            @endswitch
                        @else
                            {{ ucfirst($task->priority) }}
                        @endif
                    </span>
                </div>
            @endif
            
            @if($task->due_date)
                <div class="task-meta-item">
                    <i class="fas fa-calendar text-gray-400"></i>
                    <span class="text-xs {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                    </span>
                    @if(\Carbon\Carbon::parse($task->due_date)->isPast())
                        <span class="text-xs text-red-500">⚠️</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    <div class="task-card-footer">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                @if($task->comments && $task->comments->count() > 0)
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-comments mr-1"></i>
                        {{ $task->comments->count() }}
                    </span>
                @endif
                
                @if($task->subtasks && $task->subtasks->count() > 0)
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-list mr-1"></i>
                        {{ $task->subtasks->count() }}
                    </span>
                @endif
            </div>
            
            <a href="{{ route('tasks.show', $task->id) }}" class="task-view-btn group-hover:bg-primary-50">
                <i class="fas fa-eye mr-1"></i>
                Voir
            </a>
        </div>
    </div>
</div>

<style>
    .task-card {
        @apply bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-200;
    }
    
    .task-card-header {
        @apply p-3 border-b border-gray-100;
    }
    
    .task-title {
        @apply text-sm font-medium text-gray-900 mb-2 line-clamp-2 leading-tight;
    }
    
    .task-status-badge {
        @apply flex justify-end;
    }
    
    .task-status {
        @apply text-xs font-medium px-2 py-1 rounded-full flex items-center;
    }
    
    .task-status-todo {
        @apply bg-gray-100 text-gray-700;
    }
    
    .task-status-in-progress {
        @apply bg-blue-100 text-blue-700;
    }
    
    .task-status-done {
        @apply bg-green-100 text-green-700;
    }
    
    .task-status-blocked {
        @apply bg-red-100 text-red-700;
    }
    
    .task-card-body {
        @apply p-3 flex-1;
    }
    
    .task-description {
        @apply text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed;
    }
    
    .task-meta {
        @apply space-y-2;
    }
    
    .task-meta-item {
        @apply flex items-center space-x-2;
    }
    
    .priority-indicator {
        @apply text-sm;
    }
    
    .task-card-footer {
        @apply p-3 border-t border-gray-100;
    }
    
    .task-view-btn {
        @apply text-xs text-blue-600 hover:text-blue-700 font-medium px-2 py-1 rounded-md transition-all duration-200;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .task-card-header {
            @apply p-2;
        }
        
        .task-card-body {
            @apply p-2;
        }
        
        .task-card-footer {
            @apply p-2;
        }
        
        .task-title {
            @apply text-xs;
        }
        
        .task-status {
            @apply text-xs px-1.5 py-0.5;
        }
    }
</style>