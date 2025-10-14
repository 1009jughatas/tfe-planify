<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tâches Planify - {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2c3e50;
            background: #fff;
        }
        
        .header {
            background: #34495e;
            color: white;
            padding: 25px 30px;
            margin-bottom: 25px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 300;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        
        .header-subtitle {
            font-size: 12px;
            opacity: 0.8;
            font-weight: 300;
        }
        
        .content {
            padding: 0 30px;
        }
        
        .user-section {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .user-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .user-email {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #ecf0f1;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #3498db;
            margin-bottom: 8px;
            display: block;
        }
        
        .stat-label {
            font-size: 11px;
            color: #7f8c8d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section-divider {
            border: none;
            height: 1px;
            background: #ecf0f1;
            margin: 30px 0 25px 0;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .task-card {
            background: white;
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .task-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.3;
            flex: 1;
            margin-right: 20px;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .status-todo { background: #fff3cd; color: #856404; }
        .status-active { background: #d1ecf1; color: #0c5460; }
        .status-in-progress { background: #d4edda; color: #155724; }
        .status-completed { background: #d5f4e6; color: #27ae60; }
        .status-blocked { background: #f8d7da; color: #721c24; }
        
        .project-info {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 3px solid #3498db;
        }
        
        .project-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 12px;
        }
        
        .task-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .detail-item {
            font-size: 12px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 3px;
            display: block;
        }
        
        .detail-value {
            color: #7f8c8d;
        }
        
        .priority-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 5px;
        }
        
        .priority-low { background: #d5f4e6; color: #27ae60; }
        .priority-medium { background: #fff3cd; color: #856404; }
        .priority-high { background: #f8d7da; color: #721c24; }
        .priority-urgent { background: #f8d7da; color: #721c24; }
        
        .comments-section {
            border-top: 1px solid #ecf0f1;
            padding-top: 15px;
        }
        
        .comments-header {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
        }
        
        .comment-item {
            background: #f8f9fa;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            border-left: 3px solid #95a5a6;
        }
        
        .comment-content {
            font-size: 12px;
            color: #2c3e50;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .comment-meta {
            font-size: 10px;
            color: #7f8c8d;
        }
        
        .footer {
            margin-top: 50px;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #ecf0f1;
            color: #95a5a6;
            font-size: 10px;
        }
        
        .footer-brand {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            .header {
                background: #34495e !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Mes Tâches</h1>
        <p class="header-subtitle">Rapport généré le {{ $export_date }}</p>
    </div>

    <div class="content">
        <!-- User Section -->
        <div class="user-section">
            <div class="user-name">{{ $user->name }}</div>
            <div class="user-email">{{ $user->email }}</div>
        </div>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-item">
                <span class="stat-number">{{ $tasks->count() }}</span>
                <div class="stat-label">Tâches Totales</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $tasks->where('status', 'todo')->count() }}</span>
                <div class="stat-label">À Faire</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $tasks->where('status', 'in-progress')->count() }}</span>
                <div class="stat-label">En Cours</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $tasks->where('status', 'completed')->count() }}</span>
                <div class="stat-label">Terminées</div>
            </div>
        </div>

        <hr class="section-divider">

        <!-- Tasks Section -->
        @if($tasks->count() > 0)
            @foreach($tasks as $index => $task)
                @if($index > 0 && $index % 3 == 0)
                    <div class="page-break"></div>
                @endif
                
                <div class="task-card">
                    <div class="task-header">
                        <div class="task-title">
                            {{ $task->title }}
                            @if($task->priority)
                                <span class="priority-badge priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            @endif
                        </div>
                        <div class="status-badge status-{{ $task->status }}">
                            {{ ucfirst($task->status) }}
                        </div>
                    </div>
                    
                    <div class="project-info">
                        <span class="project-name">{{ $task->project->name }}</span>
                    </div>
                    
                    <div class="task-details">
                        @if($task->due_date)
                            <div class="detail-item">
                                <span class="detail-label">Échéance</span>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y à H:i') }}</div>
                            </div>
                        @endif
                        <div class="detail-item">
                            <span class="detail-label">Créée le</span>
                            <div class="detail-value">{{ $task->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @if($task->assigned_to && $task->assignedUser)
                            <div class="detail-item">
                                <span class="detail-label">Assignée à</span>
                                <div class="detail-value">{{ $task->assignedUser->name }}</div>
                            </div>
                        @endif
                    </div>
                    
                    @if($task->description)
                        <div class="task-details">
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <span class="detail-label">Description</span>
                                <div class="detail-value">{{ $task->description }}</div>
                            </div>
                        </div>
                    @endif
                    
                    @if($task->comments->count() > 0)
                        <div class="comments-section">
                            <div class="comments-header">
                                Commentaires ({{ $task->comments->count() }})
                            </div>
                            @foreach($task->comments->take(3) as $comment)
                                <div class="comment-item">
                                    <div class="comment-content">{{ $comment->content }}</div>
                                    <div class="comment-meta">
                                        Par {{ $comment->user ? $comment->user->name : 'Utilisateur supprimé' }} 
                                        le {{ $comment->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            @endforeach
                            @if($task->comments->count() > 3)
                                <div class="comment-item" style="background: #ecf0f1; color: #7f8c8d; text-align: center;">
                                    ... et {{ $task->comments->count() - 3 }} autres commentaires
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="task-card">
                <div style="text-align: center; color: #7f8c8d; font-style: italic; padding: 40px;">
                    Aucune tâche trouvée
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-brand">Planify</div>
        <div>Rapport généré le {{ $export_date }}</div>
    </div>
</body>
</html>