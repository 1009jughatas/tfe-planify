<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Planify - {{ $user->name }}</title>
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
        
        .projects-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .project-card {
            background: white;
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            transition: box-shadow 0.2s;
        }
        
        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .project-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.3;
            flex: 1;
            margin-right: 15px;
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
        
        .status-active { background: #d5f4e6; color: #27ae60; }
        .status-completed { background: #e8f4f8; color: #2980b9; }
        .status-planning { background: #fef9e7; color: #f39c12; }
        .status-on-hold { background: #fadbd8; color: #e74c3c; }
        
        .project-meta {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .meta-item {
            margin-bottom: 4px;
        }
        
        .meta-label {
            font-weight: 500;
            color: #34495e;
        }
        
        .tasks-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            font-size: 11px;
            color: #2c3e50;
        }
        
        .tasks-count {
            font-weight: 600;
            color: #3498db;
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
        <h1>Dashboard Planify</h1>
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
                <span class="stat-number">{{ $stats['total_projects'] ?? 0 }}</span>
                <div class="stat-label">Projets</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['completed_projects'] ?? 0 }}</span>
                <div class="stat-label">Terminés</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['total_tasks'] ?? 0 }}</span>
                <div class="stat-label">Tâches</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['completed_tasks'] ?? 0 }}</span>
                <div class="stat-label">Tâches Terminées</div>
            </div>
        </div>

        <hr class="section-divider">

        <!-- Projects Section -->
        <h2 class="section-title">Mes Projets</h2>
        
        @if($projects->count() > 0)
            <div class="projects-grid">
                @foreach($projects as $index => $project)
                    @if($index > 0 && $index % 4 == 0)
                        <div class="page-break"></div>
                    @endif
                    
                    <div class="project-card">
                        <div class="project-header">
                            <div class="project-title">{{ $project->name }}</div>
                            <div class="status-badge status-{{ $project->status }}">
                                {{ ucfirst($project->status) }}
                            </div>
                        </div>
                        
                        <div class="project-meta">
                            @if($project->start_date)
                                <div class="meta-item">
                                    <span class="meta-label">Début:</span> {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                </div>
                            @endif
                            @if($project->end_date)
                                <div class="meta-item">
                                    <span class="meta-label">Fin:</span> {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="tasks-info">
                            <span class="tasks-count">{{ $project->tasks->count() }} tâches</span>
                            @if($project->tasks->where('status', 'completed')->count() > 0)
                                • {{ $project->tasks->where('status', 'completed')->count() }} terminées
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="project-card">
                <div style="text-align: center; color: #7f8c8d; font-style: italic; padding: 20px;">
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
