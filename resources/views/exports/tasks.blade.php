<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Tâches - Planify</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .user-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }
        
        .user-info h2 {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
        }
        
        .summary-stats {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
        
        .summary-stat {
            text-align: center;
        }
        
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
        }
        
        .task-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .task-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            flex: 1;
        }
        
        .task-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 10px;
        }
        
        .status-todo { background: #ffebee; color: #c62828; }
        .status-active { background: #e8f5e8; color: #2e7d32; }
        .status-in-progress { background: #fff3e0; color: #f57c00; }
        .status-completed { background: #e8f5e8; color: #2e7d32; }
        .status-blocked { background: #ffebee; color: #c62828; }
        
        .task-details {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .project-info {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-bottom: 10px;
        }
        
        .project-name {
            font-weight: bold;
            color: #667eea;
        }
        
        .comments-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
        }
        
        .comments-header {
            font-size: 11px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .comment-item {
            background: #f8f9fa;
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 10px;
        }
        
        .comment-content {
            margin-bottom: 3px;
        }
        
        .comment-date {
            color: #666;
            font-style: italic;
        }
        
        .footer {
            margin-top: 40px;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 10px;
        }
        
        .premium-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ff8c00 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .priority-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 5px;
        }
        
        .priority-low { background: #e8f5e8; color: #2e7d32; }
        .priority-medium { background: #fff3e0; color: #f57c00; }
        .priority-high { background: #ffebee; color: #c62828; }
        .priority-urgent { background: #ffebee; color: #c62828; }
        
        @media print {
            .header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📝 Export Tâches Planify</h1>
        <p>Rapport Premium - {{ $export_date }}</p>
    </div>

    <!-- User Information -->
    <div class="user-info">
        <h2>👤 Informations Utilisateur</h2>
        <p><strong>Nom :</strong> {{ $user->name }}</p>
        <p><strong>Email :</strong> {{ $user->email }}</p>
        <p><strong>Statut :</strong> <span class="premium-badge">Premium</span></p>
        <p><strong>Date d'export :</strong> {{ $export_date }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="section-title">📊 Résumé des Tâches</div>
    <div class="summary-stats">
        <div class="summary-stat">
            <div class="summary-number">{{ $tasks->count() }}</div>
            <div class="summary-label">Tâches Totales</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $tasks->where('status', 'todo')->count() }}</div>
            <div class="summary-label">À Faire</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $tasks->where('status', 'in-progress')->count() }}</div>
            <div class="summary-label">En Cours</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $tasks->where('status', 'completed')->count() }}</div>
            <div class="summary-label">Terminées</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $tasks->where('status', 'blocked')->count() }}</div>
            <div class="summary-label">Bloquées</div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="section-title">📋 Détail des Tâches</div>
    
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
                    <div class="task-status status-{{ $task->status }}">
                        {{ ucfirst($task->status) }}
                    </div>
                </div>
                
                <div class="project-info">
                    <strong>Projet :</strong> <span class="project-name">{{ $task->project->name }}</span>
                </div>
                
                <div class="task-details">
                    @if($task->description)
                        <p><strong>Description :</strong> {{ $task->description }}</p>
                    @endif
                    
                    @if($task->due_date)
                        <p><strong>Échéance :</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y à H:i') }}</p>
                    @endif
                    
                    <p><strong>Créée le :</strong> {{ $task->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Dernière modification :</strong> {{ $task->updated_at->format('d/m/Y à H:i') }}</p>
                    
                    @if($task->assigned_to)
                        <p><strong>Assignée à :</strong> {{ $task->assignedUser ? $task->assignedUser->name : 'Utilisateur supprimé' }}</p>
                    @endif
                </div>
                
                @if($task->comments->count() > 0)
                    <div class="comments-section">
                        <div class="comments-header">
                            💬 Commentaires ({{ $task->comments->count() }})
                        </div>
                        @foreach($task->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-content">{{ $comment->content }}</div>
                                <div class="comment-date">
                                    Par {{ $comment->user ? $comment->user->name : 'Utilisateur supprimé' }} 
                                    le {{ $comment->created_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="task-card">
            <p style="text-align: center; color: #666; font-style: italic;">
                Aucune tâche trouvée.
            </p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>📄 Export généré par Planify Premium</p>
        <p>© {{ date('Y') }} Planify - Tous droits réservés</p>
        <p>Ce document a été généré automatiquement le {{ $export_date }}</p>
    </div>
</body>
</html>
