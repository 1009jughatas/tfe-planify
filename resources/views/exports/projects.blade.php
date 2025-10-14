<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Projets - Planify</title>
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
        
        .project-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .project-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .project-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-planning { background: #e3f2fd; color: #1976d2; }
        .status-active { background: #e8f5e8; color: #2e7d32; }
        .status-completed { background: #f3e5f5; color: #7b1fa2; }
        .status-on-hold { background: #fff3e0; color: #f57c00; }
        
        .project-details {
            font-size: 11px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .tasks-section {
            margin-top: 15px;
        }
        
        .tasks-header {
            font-size: 13px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .task-item {
            background: #f8f9fa;
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        .task-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        
        .task-details {
            color: #666;
            font-size: 10px;
        }
        
        .task-status {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-left: 5px;
        }
        
        .task-status-todo { background: #ffebee; color: #c62828; }
        .task-status-active { background: #e8f5e8; color: #2e7d32; }
        .task-status-in-progress { background: #fff3e0; color: #f57c00; }
        .task-status-completed { background: #e8f5e8; color: #2e7d32; }
        .task-status-blocked { background: #ffebee; color: #c62828; }
        
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
        
        .summary-stats {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
        <h1>📁 Export Projets Planify</h1>
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
    <div class="section-title">📊 Résumé des Projets</div>
    <div class="summary-stats">
        <div class="summary-stat">
            <div class="summary-number">{{ $projects->count() }}</div>
            <div class="summary-label">Projets Totaux</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $projects->where('status', 'active')->count() }}</div>
            <div class="summary-label">Actifs</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $projects->where('status', 'completed')->count() }}</div>
            <div class="summary-label">Terminés</div>
        </div>
        <div class="summary-stat">
            <div class="summary-number">{{ $projects->sum(function($p) { return $p->tasks->count(); }) }}</div>
            <div class="summary-label">Tâches Totales</div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="section-title">📋 Détail des Projets</div>
    
    @if($projects->count() > 0)
        @foreach($projects as $index => $project)
            @if($index > 0 && $index % 2 == 0)
                <div class="page-break"></div>
            @endif
            
            <div class="project-card">
                <div class="project-header">
                    <div class="project-title">{{ $project->name }}</div>
                    <div class="project-status status-{{ $project->status }}">
                        {{ ucfirst($project->status) }}
                    </div>
                </div>
                
                <div class="project-details">
                    <p><strong>Description :</strong> {{ $project->description ?: 'Aucune description' }}</p>
                    <p><strong>Priorité :</strong> {{ ucfirst($project->priority ?: 'Normale') }}</p>
                    @if($project->start_date)
                        <p><strong>Date de début :</strong> {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</p>
                    @endif
                    @if($project->end_date)
                        <p><strong>Date de fin :</strong> {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</p>
                    @endif
                    <p><strong>Créé le :</strong> {{ $project->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Dernière modification :</strong> {{ $project->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
                
                @if($project->tasks->count() > 0)
                    <div class="tasks-section">
                        <div class="tasks-header">
                            📝 Tâches ({{ $project->tasks->count() }} total)
                        </div>
                        @foreach($project->tasks as $task)
                            <div class="task-item">
                                <div class="task-title">
                                    {{ $task->title }}
                                    <span class="task-status task-status-{{ $task->status }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                                <div class="task-details">
                                    @if($task->description)
                                        <p><strong>Description :</strong> {{ $task->description }}</p>
                                    @endif
                                    @if($task->due_date)
                                        <p><strong>Échéance :</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</p>
                                    @endif
                                    <p><strong>Priorité :</strong> {{ ucfirst($task->priority ?: 'Normale') }}</p>
                                    <p><strong>Commentaires :</strong> {{ $task->comments->count() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="tasks-section">
                        <p style="color: #666; font-style: italic; text-align: center;">
                            Aucune tâche dans ce projet.
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="project-card">
            <p style="text-align: center; color: #666; font-style: italic;">
                Aucun projet trouvé.
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
