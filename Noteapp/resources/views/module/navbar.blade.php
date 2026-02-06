<nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-graduation-cap me-3"></i>UniNotes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Basculer la navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

    
            <div id="mainNav" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fonctionnalites">Fonctionnalités</a></li>
                    <li class="nav-item"><a class="nav-link" href="#roles">Rôles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>

                <div class="d-flex ms-lg-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-custom me-2">Tableau de bord</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary-custom">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-custom me-2">Connexion</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>