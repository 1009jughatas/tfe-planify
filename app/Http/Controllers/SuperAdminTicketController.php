<?php

namespace App\Http\Controllers;

use App\Models\TicketSupport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isSuperAdmin']);
    }

    /**
     * Afficher tous les tickets de support
     */
    public function index(Request $request)
    {
        $query = TicketSupport::with(['user', 'repondPar']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('objet', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => TicketSupport::count(),
            'ouverts' => TicketSupport::where('statut', 'ouvert')->count(),
            'fermes' => TicketSupport::where('statut', 'ferme')->count(),
            'repondus' => TicketSupport::whereNotNull('reponse')->count(),
        ];

        return view('superadmin.tickets.index', compact('tickets', 'stats'));
    }

    /**
     * Répondre à un ticket
     */
    public function repondre(Request $request, $id)
    {
        $request->validate([
            'reponse' => 'required|string|min:10',
            'statut' => 'required|in:ouvert,ferme'
        ]);

        $ticket = TicketSupport::findOrFail($id);

        $ticket->update([
            'reponse' => $request->reponse,
            'statut' => $request->statut,
            'repond_par' => Auth::id(),
            'repond_le' => now(),
        ]);

        return redirect()->route('superadmin.tickets.index')
            ->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Afficher les détails d'un ticket
     */
    public function show($id)
    {
        $ticket = TicketSupport::with(['user', 'repondPar'])->findOrFail($id);
        return view('superadmin.tickets.show', compact('ticket'));
    }
}