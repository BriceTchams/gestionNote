<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion </title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .btn-group .btn {
            transition: all 0.3s ease;
        }
        .btn-group .btn.active {
            background-color: #0d6efd;
            color: white;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .formcon{
            margin-top: 100px;
        }
    </style>
</head>
<body>
@include('module.navbar')

    <div class="container formcon mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark">
                        <h3 class="text-center bg-dark mb-0">Connexion</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <!-- CSRF token removed for standalone HTML -->
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Login</label>
                                <input type="text" class="form-control" 
                                       id="login" name="login" 
                                       placeholder="Entrez votre login" required autofocus maxlength="10">
                                <div class="invalid-feedback" id="emailError" style="display: none;">
                                    Veuillez entrer un email ou identifiant valide.
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password" name="password" placeholder="Entrez votre mot de passe" required maxlength="8">
                                <div class="invalid-feedback" id="passwordError" style="display: none;">
                                    Le mot de passe est requis.
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                            
                            <div class="d-grid  mx-2 d-flex text-center justify-content-center gap-2">
                                <button type="reset" class="btn btn-danger text-center  ">Annuler</button>
                                <button type="submit" class="btn btn-primary text-center  ">Se connecter</button>

                            </div>
                            
                            <div class="mt-3 text-center">
                                <!-- Password reset link commented out -->
                                <!-- <a href="#" class="text-decoration-none">
                                    Mot de passe oublié ?
                                </a> -->
                            </div>
                            
                            <hr class="my-4">
                            
                          
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('module.footer')


    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for form validation and button toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle student/teacher button toggle
            const studentBtn = document.getElementById('studentBtn');
            const teacherBtn = document.getElementById('teacherBtn');
            
            studentBtn.addEventListener('click', function() {
                studentBtn.classList.add('active');
                studentBtn.classList.remove('btn-outline-primary');
                studentBtn.classList.add('btn-primary');
                
                teacherBtn.classList.remove('active');
                teacherBtn.classList.remove('btn-secondary');
                teacherBtn.classList.add('btn-outline-secondary');
                
                // You could add logic here to change form behavior based on user type
                console.log('Mode étudiant sélectionné');
            });
            
            teacherBtn.addEventListener('click', function() {
                teacherBtn.classList.add('active');
                teacherBtn.classList.remove('btn-outline-secondary');
                teacherBtn.classList.add('btn-secondary');
                
                studentBtn.classList.remove('active');
                studentBtn.classList.remove('btn-primary');
                studentBtn.classList.add('btn-outline-primary');
                
                // You could add logic here to change form behavior based on user type
                console.log('Mode enseignant sélectionné');
            });
            
           
        });
    </script>
</body>
</html>