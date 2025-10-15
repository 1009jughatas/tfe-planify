<?php

namespace App\Http\Controllers;

use App\Models\TicketSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des tickets de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();
        $tickets = TicketSupport::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    /**
     * Afficher le formulaire de création de ticket
     */
    public function create()
    {
        return view('support.create');
    }

    /**
     * Enregistrer un nouveau ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'objet' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ], [
            'objet.required' => 'L\'objet du ticket est obligatoire.',
            'objet.max' => 'L\'objet ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du problème est obligatoire.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
        ]);

        $ticket = TicketSupport::create([
            'user_id' => Auth::id(),
            'objet' => $request->objet,
            'description' => $request->description,
            'statut' => 'ouvert',
        ]);

        return redirect()->route('support.index')
            ->with('success', 'Votre ticket de support a été créé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }

    /**
     * Afficher les détails d'un ticket
     */
    public function show(TicketSupport $ticket)
    {
        // Vérifier que l'utilisateur peut voir ce ticket
        if ($ticket->user_id !== Auth::id() && !Auth::user()->is_super_admin()) {
            abort(403, 'Vous ne pouvez pas voir ce ticket.');
        }

        return view('support.show', compact('ticket'));
    }
}