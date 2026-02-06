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
                        <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">DML</div>
                        <span class="text-sm font-medium text-gray-700">Dr. Martin Leroy</span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </div>
                </div>
            </header>

            <div class="bg-indigo-600 rounded-[2rem] p-10 text-white flex justify-between items-center mb-8 shadow-lg shadow-indigo-200">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Département Informatique 🎓</h1>
                    <p class="text-indigo-100">Gestion des filières, enseignants et publications</p>
                </div>
                <a href="{{ route('departement.pv') }}" class="bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur-md px-6 py-3 rounded-xl transition flex items-center gap-2">
                    Publier un PV <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-sm font-medium">Filières</p><p class="text-3xl font-bold">3</p></div>
                    <div class="bg-blue-50 p-3 rounded-xl text-blue-600"><i class="fas fa-book-open text-xl"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-sm font-medium">Classes</p><p class="text-3xl font-bold">5</p></div>
                    <div class="bg-purple-50 p-3 rounded-xl text-purple-600"><i class="fas fa-graduation-cap text-xl"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-sm font-medium">Enseignants</p><p class="text-3xl font-bold">4</p></div>
                    <div class="bg-green-50 p-3 rounded-xl text-green-600"><i class="fas fa-users text-xl"></i></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div><p class="text-gray-400 text-sm font-medium">Étudiants</p><p class="text-3xl font-bold">450</p></div>
                    <div class="bg-orange-50 p-3 rounded-xl text-orange-600"><i class="fas fa-chart-line text-xl"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="mb-6"><h3 class="font-bold text-gray-800">Répartition des étudiants</h3><p class="text-xs text-gray-400 font-medium uppercase mt-1 tracking-wider">Par filière</p></div>
                    <div class="h-[250px] flex justify-center"><canvas id="repChart"></canvas></div>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="mb-6"><h3 class="font-bold text-gray-800">Statut des PV</h3><p class="text-xs text-gray-400 font-medium uppercase mt-1 tracking-wider">Publications de notes</p></div>
                    <div class="h-[250px] flex items-end pb-4"><canvas id="statChart"></canvas></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        new Chart(document.getElementById('repChart'), {
            type: 'doughnut',
            data: {
                labels: ['GL: 92', 'RS: 40', 'IA: 28'],
                datasets: [{
                    data: [92, 40, 28],
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#22c55e'],
                    borderWidth: 0, cutout: '75%'
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });

        new Chart(document.getElementById('statChart'), {
            type: 'bar',
            data: {
                labels: ['Publiés', 'En attente'],
                datasets: [{
                    label: 'Nombre de PV',
                    data: [2, 2],
                    backgroundColor: ['#22c55e', '#f59e0b'],
                    borderRadius: 8
                }]
            },
            options: { scales: { y: { beginAtZero: true, max: 2 } }, plugins: { legend: { display: false } } }
        });
    </script>
</body>
</html>