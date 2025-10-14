<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets Planify - {{ $user->name }}</title>
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
            grid-template-columns: repeat(3, 1fr);
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
        
        .project-card {
            background: white;
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .project-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.3;
            flex: 1;
            margin-right: 20px;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .status-active { background: #d5f4e6; color: #27ae60; }
        .status-completed { background: #e8f4f8; color: #2980b9; }
        .status-planning { background: #fef9e7; color: #f39c12; }
        .status-on-hold { background: #fadbd8; color: #e74c3c; }
        
        .project-description {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .project-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .meta-item {
            font-size: 12px;
        }
        
        .meta-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 3px;
            display: block;
        }
        
        .meta-value {
            color: #7f8c8d;
        }
        
        .tasks-section {
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
        }
        
        .tasks-header {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .task-item {
            background: #f8f9fa;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            border-left: 3px solid #3498db;
        }
        
        .task-title {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .task-meta {
            font-size: 11px;
            color: #7f8c8d;
        }
        
        .task-status {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 8px;
        }
        
        .task-status-todo { background: #fff3cd; color: #856404; }
        .task-status-active { background: #d1ecf1; color: #0c5460; }
        .task-status-in-progress { background: #d4edda; color: #155724; }
        .task-status-completed { background: #d5f4e6; color: #27ae60; }
        .task-status-blocked { background: #f8d7da; color: #721c24; }
        
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
        <h1>Mes Projets</h1>
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
                <span class="stat-number">{{ $projects->count() }}</span>
                <div class="stat-label">Projets Totaux</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $projects->where('status', 'active')->count() }}</span>
                <div class="stat-label">En Cours</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $projects->where('status', 'completed')->count() }}</span>
                <div class="stat-label">Terminés</div>
            </div>
        </div>

        <hr class="section-divider">

        <!-- Projects Section -->
        @if($projects->count() > 0)
            @foreach($projects as $index => $project)
                @if($index > 0 && $index % 2 == 0)
                    <div class="page-break"></div>
                @endif
                
                <div class="project-card">
                    <div class="project-header">
                        <div class="project-title">{{ $project->name }}</div>
                        <div class="status-badge status-{{ $project->status }}">
                            {{ ucfirst($project->status) }}
                        </div>
                    </div>
                    
                    @if($project->description)
                        <div class="project-description">
                            {{ $project->description }}
                        </div>
                    @endif
                    
                    <div class="project-meta">
                        <div class="meta-item">
                            <span class="meta-label">Priorité</span>
                            <div class="meta-value">{{ ucfirst($project->priority ?: 'Normale') }}</div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Créé le</span>
                            <div class="meta-value">{{ $project->created_at->format('d/m/Y') }}</div>
                        </div>
                        @if($project->start_date)
                            <div class="meta-item">
                                <span class="meta-label">Date de début</span>
                                <div class="meta-value">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</div>
                            </div>
                        @endif
                        @if($project->end_date)
                            <div class="meta-item">
                                <span class="meta-label">Date de fin</span>
                                <div class="meta-value">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</div>
                            </div>
                        @endif
                    </div>
                    
                    @if($project->tasks->count() > 0)
                        <div class="tasks-section">
                            <div class="tasks-header">
                                Tâches ({{ $project->tasks->count() }})
                            </div>
                            @foreach($project->tasks->take(5) as $task)
                                <div class="task-item">
                                    <div class="task-title">
                                        {{ $task->title }}
                                        <span class="task-status task-status-{{ $task->status }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </div>
                                    @if($task->due_date)
                                        <div class="task-meta">
                                            Échéance: {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @if($project->tasks->count() > 5)
                                <div class="task-item" style="background: #ecf0f1; color: #7f8c8d; text-align: center;">
                                    ... et {{ $project->tasks->count() - 5 }} autres tâches
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="project-card">
                <div style="text-align: center; color: #7f8c8d; font-style: italic; padding: 40px;">
                    Aucun projet créé
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