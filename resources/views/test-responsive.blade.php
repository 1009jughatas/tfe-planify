<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test Responsive
        </h2>
    </x-slot>

    <div class="container py-6 px-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2 mb-4">Test de Responsivité</h1>
                <p class="text-muted mb-4">Cette page permet de tester la responsivité de l'application sur différents appareils.</p>
            </div>
        </div>

        <!-- Test des breakpoints -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="h4 mb-3">Breakpoints Bootstrap</h3>
                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">XS</h5>
                                <p class="card-text">Extra Small<br>&lt; 576px</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">SM</h5>
                                <p class="card-text">Small<br>≥ 576px</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">MD</h5>
                                <p class="card-text">Medium<br>≥ 768px</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">LG</h5>
                                <p class="card-text">Large<br>≥ 992px</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test des cartes -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="h4 mb-3">Cartes Responsives</h3>
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Carte 1</h5>
                                <p class="card-text">Contenu de la première carte avec du texte pour tester la hauteur.</p>
                                <a href="#" class="btn btn-primary">Action</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Carte 2</h5>
                                <p class="card-text">Contenu de la deuxième carte avec du texte pour tester la hauteur.</p>
                                <a href="#" class="btn btn-success">Action</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Carte 3</h5>
                                <p class="card-text">Contenu de la troisième carte avec du texte pour tester la hauteur.</p>
                                <a href="#" class="btn btn-warning">Action</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test des boutons -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="h4 mb-3">Boutons Responsives</h3>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button class="btn btn-primary">Bouton Principal</button>
                    <button class="btn btn-secondary">Bouton Secondaire</button>
                    <button class="btn btn-success">Bouton Succès</button>
                    <button class="btn btn-danger">Bouton Danger</button>
                </div>
            </div>
        </div>

        <!-- Test des formulaires -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="h4 mb-3">Formulaires Responsives</h3>
                <form>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" placeholder="Votre nom">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="votre@email.com">
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="3" placeholder="Votre message"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Test de la navigation mobile -->
        <div class="row">
            <div class="col-12">
                <h3 class="h4 mb-3">Navigation Mobile</h3>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Sur mobile, utilisez le bouton hamburger en haut à gauche pour accéder au menu de navigation.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
