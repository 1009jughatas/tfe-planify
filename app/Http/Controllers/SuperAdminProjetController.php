<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Company;
use Illuminate\Http\Request;

class SuperAdminProjetController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['user', 'company', 'tasks']);

        // Filtres
        if ($request->filled('type')) {
            if ($request->type === 'entreprise') {
                $query->whereNotNull('company_id');
            } else {
                $query->whereNull('company_id');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $projects = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Project::count(),
            'completed' => Project::where('status', 'completed')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'pending' => Project::where('status', 'pending')->count(),
        ];

        $companies = Company::all();

        return view('superadmin.projets.index', compact('projects', 'stats', 'companies'));
    }

    public function show(Project $project)
    {
        $project->load(['user', 'company', 'tasks.assignedUser', 'tasks.author']);
        
        return view('superadmin.projets.show', compact('project'));
    }
}