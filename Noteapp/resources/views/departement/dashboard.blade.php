<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Tableau de Bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#f8f9fa] font-sans">
    <div class="flex">
           @include('departement.sidebar');

        <main class="ml-64 flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-lg font-semibold text-gray-700">Tableau de bord</h2>
                <div class="flex items-center gap-5">
                    <i class="far fa-bell text-gray-400 cursor-pointer"></i>
                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border">
                        <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">
                            {{ substr($departement->nom_Departement ?? 'D', 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ $departement->chef_Departement ?? 'Chef de département' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </div>
                </div>
            </header>

            <div class="bg-indigo-600 rounded-[2rem] p-10 text-white flex justify-between items-center mb-8 shadow-lg shadow-indigo-200">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $departement->nom_Departement ?? 'Département' }} 🎓</h1>
                    <p class="text-indigo-100">Gestion des filières, enseignants et publications</p>
                </div>
                <a href="{{ route('departement.pv') }}" class="bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur-md px-6 py-3 rounded-xl transition flex items-center gap-2">
                    Publier un PV <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Filières</p><p class="text-2xl font-bold">{{ $stats['filieres_count'] }}</p></div>
                    <div class="bg-blue-50 p-3 rounded-xl text-blue-600"><i class="fas fa-book-open text-lg"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Classes</p><p class="text-2xl font-bold">{{ $stats['classes_count'] }}</p></div>
                    <div class="bg-purple-50 p-3 rounded-xl text-purple-600"><i class="fas fa-graduation-cap text-lg"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Groupes UE</p><p class="text-2xl font-bold">{{ $stats['groupes_count'] }}</p></div>
                    <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600"><i class="fas fa-layer-group text-lg"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">UEs</p><p class="text-2xl font-bold">{{ $stats['ues_count'] }}</p></div>
                    <div class="bg-emerald-50 p-3 rounded-xl text-emerald-600"><i class="fas fa-book text-lg"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Enseignants</p><p class="text-2xl font-bold">{{ $stats['enseignants_count'] }}</p></div>
                    <div class="bg-green-50 p-3 rounded-xl text-green-600"><i class="fas fa-users text-lg"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Étudiants</p><p class="text-2xl font-bold">{{ $stats['etudiants_count'] }}</p></div>
                    <div class="bg-orange-50 p-3 rounded-xl text-orange-600"><i class="fas fa-user-graduate text-lg"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="mb-6"><h3 class="font-bold text-gray-800">Répartition des étudiants</h3><p class="text-xs text-gray-400 font-medium uppercase mt-1 tracking-wider">Par filière</p></div>
                    <div class="h-[250px] flex justify-center"><canvas id="repChart"></canvas></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        new Chart(document.getElementById('repChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($repartition->pluck('nom_Filiere')) !!},
                datasets: [{
                    data: {!! json_encode($repartition->pluck('etudiants_count')) !!},
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4'],
                    borderWidth: 0, cutout: '75%'
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    </script>
</body>
</html>
