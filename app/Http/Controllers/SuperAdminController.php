<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\TicketSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isSuperAdmin']);
    }

    /**
     * Dashboard principal du Super Admin
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'independants' => User::where('role', 'user_independant')->count(),
            'admin_entreprise' => User::where('role', 'admin_entreprise')->count(),
            'user_entreprise' => User::where('role', 'user_entreprise')->count(),
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'total_tickets' => TicketSupport::count(),
            'open_tickets' => TicketSupport::where('statut', 'ouvert')->count(),
        ];

        // Utilisateurs récents
        $recent_users = User::with('company')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Entreprises récentes
        $recent_companies = Company::with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Tickets récents
        $recent_tickets = TicketSupport::with(['user', 'repondPar'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Projets récents
        $recent_projects = Project::with(['author', 'company'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'stats',
            'recent_users',
            'recent_companies',
            'recent_tickets',
            'recent_projects'
        ));
    }
}