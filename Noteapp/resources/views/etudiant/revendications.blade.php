<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Revendications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        :root { --primary-color:#0d6efd; --secondary-color:#6c63ff; --dark-color:#111827; --light-color:#f8f9fa; --accent-color:#20c997; }
        body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;background-color:#ffffff;color:#333;line-height:1.6}
        .navbar{background-color:#ffffff;box-shadow:0 2px 15px rgba(0,0,0,0.1);padding:15px 0}
        .navbar-brand{font-weight:700;color:var(--primary-color)!important;font-size:1.5rem}
        .sidebar{background:#0f172a;color:#ffffff;min-height:100vh;padding-top:80px}
        .sidebar .nav-link{color:#cbd5e1;padding:12px 20px;border-radius:8px;margin:5px 10px;transition:all .3s}
        .sidebar .nav-link:hover,.sidebar .nav-link.active{background:#1e293b;color:#ffffff}
        .main-content{padding:30px}
        .card{border:none;border-radius:15px;box-shadow:0 5px 15px rgba(0,0,0,0.05)}
        .card-header{background:#fff;border-bottom:1px solid #e9ecef}
        .kpi-box{background:#fff;border:1px solid #e9ecef;border-radius:15px;padding:20px}
        .kpi-title{color:#6b7280;font-weight:600}
        .kpi-value{font-size:1.8rem;font-weight:700}
        .badge-soft{background:#e9f5ff;color:#0d6efd;border:1px solid #b6dcff}
        .btn-primary-custom{background:var(--primary-color);color:#fff;border:none}
        .btn-outline-custom{border:1px solid var(--primary-color);color:var(--primary-color)}
        .list-group-item{border:1px solid #e9ecef;border-radius:12px;margin-bottom:10px}
    </style>
</head>
<body>
v  <nav class="navbar navbar-expand-lg">
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
        <div class="col-md-3 col-lg-2 sidebar d-none d-md-block">
            <div class="pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/etudiant/dashboard"><i class="fas fa-gauge-high me-2"></i>Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/etudiant/notes"><i class="fas fa-book me-2"></i>Mes Notes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/etudiant/revendications"><i class="fas fa-file-pen me-2"></i>Mes Revendications</a></li>
                </ul>
                <div class="card mt-4" style="background:#1e293b;color:#fff">
                    <div class="card-body">
                        <small class="text-muted" style="color:#cbd5e1!important">Étudiant</small>
                        <h6 class="mt-2 mb-0">Jean Dupont</h6>
                        <small class="text-muted" style="color:#cbd5e1!important">ETU2024001 - GL L3 - A</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Mes Revendications</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary-custom"><i class="fas fa-plus me-2"></i>Nouvelle revendication</button>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="kpi-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="kpi-title">En attente</span>
                            <i class="fas fa-hourglass-half text-warning"></i>
                        </div>
                        <div class="kpi-value">1</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="kpi-title">Approuvées</span>
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div class="kpi-value">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="kpi-title">Rejetées</span>
                            <i class="fas fa-times-circle text-danger"></i>
                        </div>
                        <div class="kpi-value">0</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Historique des revendications</h5>
                    <span class="badge badge-soft">Dernières mises à jour</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-3">
                                <h6 class="mb-1"><i class="fas fa-code me-2"></i>Génie Logiciel</h6>
                                <small class="text-muted">Note actuelle: 11.8/20</small>
                                <p class="mt-2 mb-0">Je pense qu\'il y a une erreur dans le calcul de ma note d\'examen. J\'ai vérifié mes réponses et je devrais avoir au moins 13.</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning">En attente</span>
                                <div class="mt-2"><small class="text-muted">Soumise le 2025-01-15</small></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>