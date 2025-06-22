@extends('layouts.app')

@section('title', 'Mentions Légales')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12 text-gray-800">
    <h1 class="text-3xl font-bold mb-8">Mentions légales de Planify</h1>

    <p class="mb-2"><strong>Nom de l'application :</strong> Planify</p>
    <p class="mb-2"><strong>Type de service :</strong> Application web de gestion de projets et de tâches collaboratives</p>
    <p class="mb-2"><strong>Éditeur du site :</strong> Justin Ghatas</p>
    <p class="mb-2"><strong>Adresse du siège social :</strong> Boulevard de l'Empereur 10, 1000 Bruxelles, Belgique</p>
    <p class="mb-6"><strong>Email de contact :</strong> <a href="mailto:info@mcedia.com" class="text-blue-600 underline">info@mcedia.com</a></p>

    <h2 class="text-2xl font-semibold mt-8 mb-4">🖥️ Hébergement</h2>
    <p class="mb-2"><strong>Hébergeur :</strong> OVH</p>
    <p class="mb-2"><strong>Adresse :</strong> 2 rue Kellermann, 59100 Roubaix, France</p>
    <p class="mb-2"><strong>Téléphone :</strong> +33 9 72 10 10 07</p>
    <p class="mb-6"><strong>Site web :</strong> <a href="https://www.ovhcloud.com" target="_blank" class="text-blue-600 underline">https://www.ovhcloud.com</a></p>

    <h2 class="text-2xl font-semibold mt-8 mb-4">👤 Données personnelles</h2>
    <p class="mb-2">Conformément au Règlement Général sur la Protection des Données (RGPD) :</p>
    <ul class="list-disc pl-5 mb-6 text-gray-700">
        <li>Les informations collectées sur Planify (nom, prénom, email, mot de passe, rôles utilisateur) sont utilisées uniquement dans le cadre du bon fonctionnement de la plateforme.</li>
        <li>Les données ne sont ni revendues, ni partagées à des tiers.</li>
        <li>Les utilisateurs peuvent à tout moment accéder, modifier ou supprimer leurs données personnelles en nous contactant à : <a href="mailto:info@mcedia.com" class="text-blue-600 underline">info@mcedia.com</a>.</li>
        <li>Les mots de passe sont stockés de manière chiffrée et sécurisée.</li>
        <li>L'application met en œuvre des mesures de sécurité techniques et organisationnelles pour protéger les données personnelles.</li>
    </ul>

    <h2 class="text-2xl font-semibold mt-8 mb-4">🍪 Cookies</h2>
    <p class="mb-6">Planify n’utilise pas de cookies publicitaires.<br>
    Seuls des cookies techniques strictement nécessaires au fonctionnement du site sont utilisés (ex : gestion de session).</p>

    <h2 class="text-2xl font-semibold mt-8 mb-4">⚖️ Propriété intellectuelle</h2>
    <p class="mb-6">Tous les contenus présents sur Planify (textes, images, codes sources, éléments graphiques) sont la propriété exclusive de l’éditeur.<br>
    Toute reproduction, modification ou diffusion, partielle ou totale, sans autorisation préalable est interdite.</p>

    <h2 class="text-2xl font-semibold mt-8 mb-4">💼 Responsabilité</h2>
    <p class="mb-6">L’éditeur met tout en œuvre pour garantir l’exactitude et la mise à jour des informations disponibles.<br>
    Toutefois, il ne peut être tenu responsable en cas d’erreurs, d’interruptions de service ou de dommages liés à l’utilisation de la plateforme.</p>

    <h2 class="text-2xl font-semibold mt-8 mb-4">📌 Contact</h2>
    <p class="mb-2"><strong>Email :</strong> <a href="mailto:info@mcedia.com" class="text-blue-600 underline">info@mcedia.com</a></p>
    <p class="mb-6"><strong>Adresse :</strong> Boulevard de l'Empereur 10, 1000 Bruxelles, Belgique</p>
</div>
@endsection
