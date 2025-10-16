<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketSupport;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Super admin peut voir tous les tickets
        if ($user->is_super_admin()) {
            $tickets = TicketSupport::with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Autres utilisateurs voient leurs propres tickets
        else {
            $tickets = TicketSupport::where('user_id', $user->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = TicketSupport::create([
            'user_id' => $user->id,
            'objet' => $validated['objet'],
            'description' => $validated['description'],
            'statut' => 'ouvert',
        ]);

        return response()->json($ticket->load('user'), 201);
    }

    public function show(TicketSupport $ticket)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->is_super_admin() && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        return response()->json($ticket->load('user', 'repondPar'));
    }

    public function update(Request $request, TicketSupport $ticket)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->is_super_admin() && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'objet' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'statut' => 'sometimes|in:ouvert,ferme',
        ]);

        $ticket->update($validated);
        return response()->json($ticket->load('user'));
    }

    public function destroy(TicketSupport $ticket)
    {
        $user = Auth::user();
        
        // Seuls les super admins peuvent supprimer des tickets
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $ticket->delete();
        return response()->json(null, 204);
    }

    /**
     * Répondre à un ticket (super admin uniquement)
     */
    public function respond(Request $request, TicketSupport $ticket)
    {
        $user = Auth::user();
        
        // Seuls les super admins peuvent répondre
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'reponse' => 'required|string',
            'statut' => 'nullable|in:ouvert,ferme',
        ]);

        $ticket->update([
            'reponse' => $validated['reponse'],
            'repond_par' => $user->id,
            'repond_le' => now(),
            'statut' => $validated['statut'] ?? $ticket->statut,
        ]);

        return response()->json($ticket->load('user', 'repondPar'));
    }

    /**
     * Fermer un ticket
     */
    public function close(TicketSupport $ticket)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->is_super_admin() && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $ticket->update(['statut' => 'ferme']);
        return response()->json($ticket->load('user'));
    }

    /**
     * Rouvrir un ticket
     */
    public function reopen(TicketSupport $ticket)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->is_super_admin() && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $ticket->update(['statut' => 'ouvert']);
        return response()->json($ticket->load('user'));
    }

    /**
     * Obtenir les statistiques des tickets
     */
    public function stats()
    {
        $user = Auth::user();
        
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $stats = [
            'total_tickets' => TicketSupport::count(),
            'open_tickets' => TicketSupport::where('statut', 'ouvert')->count(),
            'closed_tickets' => TicketSupport::where('statut', 'ferme')->count(),
            'tickets_today' => TicketSupport::whereDate('created_at', today())->count(),
            'tickets_this_week' => TicketSupport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return response()->json($stats);
    }
}