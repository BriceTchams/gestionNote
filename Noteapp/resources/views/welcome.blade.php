<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil </title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c63ff;
            --dark-color: #111827;
            --light-color: #f8f9fa;
            --accent-color: #20c997;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .hero-image {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Sections */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .section-subtitle {
            color: #666;
            text-align: center;
            max-width: 700px;
            margin: 0 auto 50px;
            font-size: 1.1rem;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 28px;
            color: white;
        }

        .feature-icon.blue { background: linear-gradient(135deg, #0d6efd 0%, #6c63ff 100%); }
        .feature-icon.green { background: linear-gradient(135deg, #20c997 0%, #3bd6a8 100%); }
        .feature-icon.orange { background: linear-gradient(135deg, #fd7e14 0%, #ff9a3d 100%); }
        .feature-icon.purple { background: linear-gradient(135deg, #6f42c1 0%, #9d6de3 100%); }

        /* Stats */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background-color: var(--dark-color);
            color: #9ca3af;
            padding: 60px 0 30px;
        }

        .footer-title {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .copyright {
            border-top: 1px solid #333;
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-section::before {
                width: 100%;
                height: 50%;
                clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 80%);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
@include('module.navbar')
    <!-- Hero Section -->
    <section id="accueil" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Système de Gestion de Notes Universitaire</h1>
                    <p class="hero-subtitle">
                        Une plateforme complète et intuitive pour la gestion académique.
                        Consultation des notes, gestion des évaluations, publication des PV,
                        et administration des départements en un seul endroit.
                    </p>
{{--                    <div class="d-flex flex-wrap gap-3">--}}
{{--                        @auth--}}
{{--                            <a href="{{ route('dashboard') }}" class="btn btn-primary-custom">--}}
{{--                                Accéder à mon espace <i class="fas fa-arrow-right ms-2"></i>--}}
{{--                            </a>--}}
{{--                        @else--}}
{{--                            <a href="{{ route('login') }}" class="btn btn-primary-custom me-2">--}}
{{--                                Se connecter <i class="fas fa-sign-in-alt ms-2"></i>--}}
{{--                            </a>--}}
{{--                            --}}{{-- <a href="{{ route('register') }}" class="btn btn-outline-custom"> --}}
{{--                                Créer un compte--}}
{{--                            </a>--}}
{{--                        @endauth--}}
{{--                    </div>--}}
                </div>
                <div class="col-lg-6 hero-image">
                    <img src="{{ asset('image/accueil_hero.png') }}"
                         alt="Campus universitaire" class="img-fluid" />
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">2,000+</div>
                        <div class="stat-label">Étudiants</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">120</div>
                        <div class="stat-label">Enseignants</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">45</div>
                        <div class="stat-label">Parcours</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Sécurité</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fonctionnalites" class="py-5">
        <div class="container">
            <h2 class="section-title">Fonctionnalités Principales</h2>
            <p class="section-subtitle">
                Découvrez les fonctionnalités complètes de notre plateforme de gestion académique
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Consultation des Notes</h4>
                        <p>
                            Accédez à vos résultats, relevés de notes et moyenne par semestre
                            après publication des PV. Interface intuitive et données sécurisées.
                        </p>
                        <a href="{{ route('login') }}" class="text-primary fw-semibold">
                            En savoir plus <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Gestion des Évaluations</h4>
                        <p>
                            Enregistrement et modification des notes, gestion des requêtes
                            de revendication. Outils complets pour les enseignants.
                        </p>
                        <a href="{{ route('login') }}" class="text-primary fw-semibold">
                            En savoir plus <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h4>Publication des PV</h4>
                        <p>
                            Publication des PV simples et PV après rattrapage pour
                            accès immédiat par les étudiants. Notification automatique.
                        </p>
                        <a href="#annonces" class="text-primary fw-semibold">
                            En savoir plus <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rôles Section -->
    <section id="roles" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Pour Tous les Acteurs Académiques</h2>
            <p class="section-subtitle">
                Une plateforme adaptée à chaque rôle dans l'écosystème universitaire
            </p>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon purple me-3">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4 class="mb-0">Étudiant</h4>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Accès aux relevés de notes et moyennes</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Visualisation des emplois du temps</li>
                            <li><i class="fas fa-check text-success me-2"></i> Notifications des nouvelles publications</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon blue me-3">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h4 class="mb-0">Enseignant</h4>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Saisie et modification des notes</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Gestion des requêtes de revendication</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Publication des résultats partiels</li>
                            <li><i class="fas fa-check text-success me-2"></i> Consultation des statistiques de classe</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon green me-3">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h4 class="mb-0">Chef de Département</h4>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Validation des PV avant publication</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Gestion des enseignants du département</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Supervision des évaluations</li>
                            <li><i class="fas fa-check text-success me-2"></i> Rapports statistiques détaillés</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon orange me-3">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h4 class="mb-0">Administrateur</h4>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Gestion complète des utilisateurs</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Configuration du système</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Supervision de toutes les opérations</li>
                            <li><i class="fas fa-check text-success me-2"></i> Sauvegarde et restauration des données</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Call to Action Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="text-white mb-4" style="font-size: 2.5rem; font-weight: 700;">
                        Prêt à simplifier votre gestion académique ?
                    </h2>
                    <p class="text-white mb-5" style="font-size: 1.2rem; opacity: 0.9;">
                        Rejoignez notre plateforme dès maintenant et découvrez comment nous pouvons transformer
                        votre expérience académique. Simple, sécurisé et efficace.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3"
                       style="border-radius: 50px; font-weight: 600; font-size: 1.1rem; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);">
                        <i class="fas fa-rocket me-2"></i> Commencez maintenant
                    </a>
                    <p class="text-white mt-4" style="opacity: 0.8;">
                        Accédez à toutes les fonctionnalités en vous connectant à votre compte.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('module.footer')

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Contact Form Submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.');
            this.reset();
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '10px 0';
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.padding = '15px 0';
                navbar.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
            }
        });
    </script>
</body>
</html>
