<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Notes</title>
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
        .kpi-card{background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));color:#fff;border-radius:15px}
        .badge-soft{background:#e9f5ff;color:#0d6efd;border:1px solid #b6dcff}
        .table thead th{background:#f8f9fa;border-top:none;font-weight:600}
        .btn-primary-custom{background:var(--primary-color);color:#fff;border:none}
        .btn-outline-custom{border:1px solid var(--primary-color);color:var(--primary-color)}
    </style>
</head>
<body>
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
        <div class="col-md-3 col-lg-2 sidebar d-none d-md-block">
            <div class="pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="{{ route('etudiant.dashboard') }}"><i class="fas fa-gauge-high me-2"></i>Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('etudiant.notes') }}"><i class="fas fa-book me-2"></i>Mes Notes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('etudiant.revendications') }}"><i class="fas fa-file-pen me-2"></i>Mes Revendications</a></li>
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
                <h1 class="h4 mb-0">Mes Notes</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-custom"><i class="fas fa-download me-2"></i>Exporter</button>
                    <button class="btn btn-outline-custom"><i class="fas fa-print me-2"></i>Imprimer</button>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Année académique</label>
                    <select class="form-select">
                        <option selected>2024-2025</option>
                        <option>2023-2024</option>
                        <option>2022-2023</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Semestre</label>
                    <select class="form-select">
                        <option>Semestre 1</option>
                        <option selected>Semestre 2</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut académique</label>
                    <div class="mt-2"><span class="badge bg-success">Admis</span></div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-lg-4">
                    <div class="kpi-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-2">Moyenne Générale</h6>
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h2 class="mb-0">13.93<span class="fs-6">/20</span></h2>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Statut</h6>
                            <span class="badge bg-success">Admis</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Publication PV</h6>
                            <span class="badge bg-success">Publié</span>
                            <div><small class="text-muted">29/01/2025</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Relevé de Notes - Semestre 1</h5>
                    <span class="badge badge-soft">Génie Logiciel - GL L3-A | Année 2024-2025</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Matière</th>
                                    <th class="text-center">Coef.</th>
                                    <th class="text-center">CC</th>
                                    <th class="text-center">TP</th>
                                    <th class="text-center">Examen</th>
                                    <th class="text-center">Moyenne</th>
                                    <th class="text-center">Validation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PAJ301</td>
                                    <td><strong>Programmation Avancée</strong><br><small class="text-muted">PA301</small></td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">14.00</td>
                                    <td class="text-center">16.00</td>
                                    <td class="text-center">13.00</td>
                                    <td class="text-center text-success">14.20</td>
                                    <td class="text-center"><span class="badge bg-success">Validé</span></td>
                                </tr>
                                <tr>
                                    <td>BUJ301</td>
                                    <td><strong>Base de Données</strong><br><small class="text-muted">BD301</small></td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">12.00</td>
                                    <td class="text-center">15.00</td>
                                    <td class="text-center">14.00</td>
                                    <td class="text-center text-success">13.60</td>
                                    <td class="text-center"><span class="badge bg-success">Validé</span></td>
                                </tr>
                                <tr>
                                    <td>RIJ301</td>
                                    <td><strong>Réseaux Informatiques</strong><br><small class="text-muted">RI301</small></td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">13.00</td>
                                    <td class="text-center">12.00</td>
                                    <td class="text-center">15.00</td>
                                    <td class="text-center text-success">15.20</td>
                                    <td class="text-center"><span class="badge bg-success">Validé</span></td>
                                </tr>
                                <tr>
                                    <td>GLJ301</td>
                                    <td><strong>Génie Logiciel</strong><br><small class="text-muted">GL301</small></td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">12.00</td>
                                    <td class="text-center">12.00</td>
                                    <td class="text-center">11.00</td>
                                    <td class="text-center text-success">11.80</td>
                                    <td class="text-center"><span class="badge bg-success">Validé</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>