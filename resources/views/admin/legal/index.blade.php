<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-gavel me-2"></i>{{ __('Gestion des Contenus Légaux') }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 mb-3">Contenus Légaux</h1>
                <p class="text-muted">Gérez les mentions légales, CGU et politique de confidentialité</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Mentions Légales -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Mentions Légales</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.legal.update') }}">
                    @csrf
                    <input type="hidden" name="type" value="mentions_legales">
                    <textarea class="form-control mb-3" 
                              name="content" 
                              rows="8" 
                              placeholder="Contenu des mentions légales...">{{ $contents['mentions_legales'] ?? '' }}</textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </form>
            </div>
        </div>

        <!-- CGU -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Conditions Générales d'Utilisation</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.legal.update') }}">
                    @csrf
                    <input type="hidden" name="type" value="cgu">
                    <textarea class="form-control mb-3" 
                              name="content" 
                              rows="8" 
                              placeholder="Contenu des CGU...">{{ $contents['cgu'] ?? '' }}</textarea>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </form>
            </div>
        </div>

        <!-- Politique de Confidentialité -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Politique de Confidentialité</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.legal.update') }}">
                    @csrf
                    <input type="hidden" name="type" value="politique_confidentialite">
                    <textarea class="form-control mb-3" 
                              name="content" 
                              rows="8" 
                              placeholder="Contenu de la politique de confidentialité...">{{ $contents['politique_confidentialite'] ?? '' }}</textarea>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </form>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="text-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
