<div class="task-card hover-lift">
    <div class="task-card-header">
        <h5 class="task-title">{{ $task->title }}</h5>
        <div class="task-status-badge">
            @php
                $statusConfig = [
                    'todo' => ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'fas fa-circle'],
                    'pending' => ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'fas fa-circle'],
                    'in-progress' => ['class' => 'bg-blue-100 text-blue-700', 'icon' => 'fas fa-play'],
                    'blocked' => ['class' => 'bg-red-100 text-red-700', 'icon' => 'fas fa-exclamation-triangle'],
                    'done' => ['class' => 'bg-green-100 text-green-700', 'icon' => 'fas fa-check-circle'],
                    'completed' => ['class' => 'bg-green-100 text-green-700', 'icon' => 'fas fa-check-circle']
                ];
                $config = $statusConfig[$task->status] ?? $statusConfig['todo'];
            @endphp
            <span class="badge-modern {{ $config['class'] }}">
                <i class="{{ $config['icon'] }} mr-1"></i>
                {{ ucfirst(str_replace('-', ' ', $task->status)) }}
            </span>
        </div>
    </div>
    
    <div class="task-card-body">
        <p class="task-description">{{ \Illuminate\Support\Str::limit($task->description, 80, '...') }}</p>
        
        <div class="task-meta">
            <div class="task-meta-item">
                <i class="fas fa-user text-gray-400"></i>
                <span class="text-sm text-gray-600">
                    {{ $task->assignedUser ? $task->assignedUser->name : 'Non assigné' }}
                </span>
            </div>
            
            @if($task->priority)
                <div class="task-meta-item">
                    @php
                        $priorityConfig = [
                            'low' => ['class' => 'text-green-600', 'icon' => 'fas fa-arrow-down'],
                            'medium' => ['class' => 'text-yellow-600', 'icon' => 'fas fa-minus'],
                            'high' => ['class' => 'text-red-600', 'icon' => 'fas fa-arrow-up']
                        ];
                        $priorityClass = $priorityConfig[$task->priority] ?? $priorityConfig['medium'];
                    @endphp
                    <i class="{{ $priorityClass['icon'] }} {{ $priorityClass['class'] }}"></i>
                    <span class="text-sm text-gray-600">{{ ucfirst($task->priority) }}</span>
                </div>
            @endif
            
            @if($task->due_date)
                <div class="task-meta-item">
                    <i class="fas fa-calendar text-gray-400"></i>
                    <span class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                    </span>
                </div>
            @endif
        </div>
    </div>
    
    <div class="task-card-footer">
        <a href="{{ route('tasks.show', $task->id) }}" class="task-view-btn">
            <i class="fas fa-eye mr-1"></i>
            Voir la tâche
        </a>
    </div>
</div>

<style>
    .task-card {
        @apply bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200;
    }
    
    .task-card-header {
        @apply p-4 border-b border-gray-100;
    }
    
    .task-title {
        @apply text-sm font-semibold text-gray-900 mb-2 line-clamp-2;
    }
    
    .task-status-badge {
        @apply flex justify-end;
    }
    
    .task-card-body {
        @apply p-4 flex-1;
    }
    
    .task-description {
        @apply text-sm text-gray-600 mb-3 line-clamp-2;
    }
    
    .task-meta {
        @apply space-y-2;
    }
    
    .task-meta-item {
        @apply flex items-center space-x-2;
    }
    
    .task-card-footer {
        @apply p-4 border-t border-gray-100;
    }
    
    .task-view-btn {
        @apply w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors duration-200;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>