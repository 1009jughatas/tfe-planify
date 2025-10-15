<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'objet',
        'description',
        'statut',
        'reponse',
        'repond_par',
        'repond_le'
    ];

    protected $casts = [
        'repond_le' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui a créé le ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'utilisateur qui a répondu au ticket
     */
    public function repondPar()
    {
        return $this->belongsTo(User::class, 'repond_par');
    }

    /**
     * Scope pour les tickets ouverts
     */
    public function scopeOuverts($query)
    {
        return $query->where('statut', 'ouvert');
    }

    /**
     * Scope pour les tickets fermés
     */
    public function scopeFermes($query)
    {
        return $query->where('statut', 'ferme');
    }

    /**
     * Vérifier si le ticket est ouvert
     */
    public function isOuvert()
    {
        return $this->statut === 'ouvert';
    }

    /**
     * Vérifier si le ticket est fermé
     */
    public function isFerme()
    {
        return $this->statut === 'ferme';
    }

    /**
     * Marquer le ticket comme fermé
     */
    public function fermer()
    {
        $this->update(['statut' => 'ferme']);
    }

    /**
     * Marquer le ticket comme ouvert
     */
    public function ouvrir()
    {
        $this->update(['statut' => 'ouvert']);
    }
}