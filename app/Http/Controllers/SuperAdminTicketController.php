<?php

namespace App\Http\Controllers;

use App\Models\TicketSupport;
use Illuminate\Http\Request;

class SuperAdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketSupport::with(['user']);

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
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('objet', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(20);

        // Statistiques
        $stats = [
            'total' => TicketSupport::count(),
            'ouvert' => TicketSupport::where('statut', 'ouvert')->count(),
            'ferme' => TicketSupport::where('statut', 'fermé')->count(),
        ];

        return view('superadmin.tickets.index', compact('tickets', 'stats'));
    }

    public function show(TicketSupport $ticket)
    {
        $ticket->load(['user']);
        
        return view('superadmin.tickets.show', compact('ticket'));
    }

    public function repondre(Request $request, $id)
    {
        $request->validate([
            'reponse' => 'required|string',
        ]);

        $ticket = TicketSupport::findOrFail($id);
        
        $ticket->update([
            'reponse' => $request->reponse,
            'statut' => 'fermé'
        ]);

        return back()->with('success', 'Réponse envoyée avec succès.');
    }
}