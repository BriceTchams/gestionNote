<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord Étudiant</title>
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
            background-color: #f8f9fa; 
            color: #333;
            line-height: 1.6;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }
        .sidebar {
            background: #0f172a;
            color: #ffffff;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding-top: 76px;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #1e293b;
            color: #ffffff;
        }
        .main-content {
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
        }
        .table th {
            border-top: none;
            background-color: #f8f9fa;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">UniNotes</a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>Étudiant Dupont
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-none d-md-block">
                <div class="pt-4">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('etudiant.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('etudiant.notes') }}">
                                <i class="fas fa-book me-2"></i>Mes Notes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('etudiant.revendications') }}">
                                <i class="fas fa-exclamation-circle me-2"></i>Mes Revendications
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Tableau de bord Étudiant</h1>
                    <div class="d-flex">
                        <a class="btn btn-primary" href="{{ route('etudiant.notes') }}">
                            <i class="fas fa-eye me-2"></i>Voir mes notes
                        </a>
                    </div>
                </div>
                <div class="p-4 mb-4" style="border-radius: 15px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white;">
                    <h5 class="mb-1">Bienvenue, Étudiant Dupont</h5>
                    <p class="mb-0">Heureux de vous revoir. Consultez vos dernières notes et notifications ci-dessous.</p>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <h5 class="mb-2">Moyenne Générale</h5>
                            <h2 class="mb-0">14.5/20</h2>
                            <small><i class="fas fa-arrow-up me-1"></i>+0.5 vs semestre dernier</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Cours en cours</h5>
                                <h2 class="card-text">6</h2>
                                <p class="card-text text-muted">Semestre 3</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Prochain examen</h5>
                                <h2 class="card-text">15 Déc</h2>
                                <p class="card-text text-muted">Algorithmique</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Documents</h5>
                                <h2 class="card-text">24</h2>
                                <p class="card-text text-muted">Non lus: 3</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Grades -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Notes récentes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matière</th>
                                        <th>Note</th>
                                        <th>Date</th>
                                        <th>Commentaire</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Algorithmique</td>
                                        <td><span class="badge bg-success">16/20</span></td>
                                        <td>10/12/2024</td>
                                        <td>Excellent travail</td>
                                        <td><span class="badge bg-success">Validé</span></td>
                                    </tr>
                                    <tr>
                                        <td>Base de données</td>
                                        <td><span class="badge bg-warning">12/20</span></td>
                                        <td>05/12/2024</td>
                                        <td>À améliorer</td>
                                        <td><span class="badge bg-warning">En attente</span></td>
                                    </tr>
                                    <tr>
                                        <td>Mathématiques</td>
                                        <td><span class="badge bg-success">15/20</span></td>
                                        <td>01/12/2024</td>
                                        <td>Très bien</td>
                                        <td><span class="badge bg-success">Validé</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events & Notifications -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Événements à venir</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Examen Algorithmique</h6>
                                            <small class="text-muted">15 Décembre 2024 - 09:00</small>
                                        </div>
                                        <span class="badge bg-primary">Salle A12</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Rendu projet BD</h6>
                                            <small class="text-muted">20 Décembre 2024 - 23:59</small>
                                        </div>
                                        <span class="badge bg-warning">En ligne</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Notifications</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Nouvelle note publiée</h6>
                                            <small class="text-muted">Algorithmique - 16/20</small>
                                        </div>
                                        <span class="badge bg-success">Nouveau</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Rappel: Projet BD</h6>
                                            <small class="text-muted">Dû le 20 Décembre</small>
                                        </div>
                                        <span class="badge bg-warning">Rappel</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Emploi du temps mis à jour</h6>
                                            <small class="text-muted">Consultez la nouvelle version</small>
                                        </div>
                                        <span class="badge bg-info">Info</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>