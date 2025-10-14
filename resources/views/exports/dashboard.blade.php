<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Dashboard - Planify</title>
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
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
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .project-title {
            font-size: 14px;
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
            margin-bottom: 10px;
        }
        
        .tasks-summary {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            font-size: 11px;
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
        <h1>📊 Dashboard Planify</h1>
        <p>Rapport d'export Premium - {{ $export_date }}</p>
    </div>

    <!-- User Information -->
    <div class="user-info">
        <h2>👤 Informations Utilisateur</h2>
        <p><strong>Nom :</strong> {{ $user->name }}</p>
        <p><strong>Email :</strong> {{ $user->email }}</p>
        <p><strong>Statut :</strong> <span class="premium-badge">Premium</span></p>
        <p><strong>Date d'export :</strong> {{ $export_date }}</p>
    </div>

    <!-- Statistics -->
    <div class="section-title">📈 Statistiques Générales</div>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_projects'] ?? 0 }}</div>
            <div class="stat-label">Projets Totaux</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['completed_projects'] ?? 0 }}</div>
            <div class="stat-label">Projets Terminés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_tasks'] ?? 0 }}</div>
            <div class="stat-label">Tâches Totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['completed_tasks'] ?? 0 }}</div>
            <div class="stat-label">Tâches Terminées</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['active_tasks'] ?? 0 }}</div>
            <div class="stat-label">Tâches Actives</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['in_progress_tasks'] ?? 0 }}</div>
            <div class="stat-label">En Cours</div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="section-title">📁 Détail des Projets</div>
    
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
                </div>
                
                <div class="tasks-summary">
                    <strong>Tâches :</strong> {{ $project->tasks->count() }} total
                    ({{ $project->tasks->where('status', 'completed')->count() }} terminées,
                    {{ $project->tasks->where('status', 'active')->count() }} actives,
                    {{ $project->tasks->where('status', 'in-progress')->count() }} en cours)
                </div>
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
