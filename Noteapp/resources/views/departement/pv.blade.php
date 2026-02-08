<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Publication PV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] font-sans">
    <div class="flex">

    @include('departement.sidebar');

        <main class="ml-64 flex-1 p-8">
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex justify-between items-center rounded-r-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-900/50 hover:text-green-900"><i class="fas fa-times"></i></button>
            </div>
            @endif

            <header class="flex justify-between items-center mb-8">
                <h2 class="text-lg font-semibold text-gray-700">Publication PV</h2>
                <div class="flex items-center gap-5">
                    <i class="far fa-bell text-gray-400 cursor-pointer"></i>
                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border shadow-sm">
                        <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">
                            {{ substr(auth()->guard('departement')->user()->chef_Departement ?? 'D', 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ auth()->guard('departement')->user()->chef_Departement ?? 'Chef de département' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </div>
                </div>
            </header>

            <h1 class="text-2xl font-bold text-gray-800">Publication des PV</h1>
            <p class="text-sm text-gray-400 mb-8">Gérez la publication des procès-verbaux de notes</p>


            <div class="flex gap-2 mb-8 bg-white p-1 rounded-xl w-fit shadow-sm border">
                <a href="{{ route('departement.pv') }}" class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-medium text-sm">Publier un PV</a>
                <a href="{{ route('departement.pv.history') }}" class="text-gray-400 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium text-sm transition">Historique</a>
            </div>

            <div class="bg-white p-10 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-xl font-bold text-gray-800">Sélection</h3>
                <p class="text-sm text-gray-400 mb-8">Choisissez l'année, la classe et le semestre pour le PV</p>

                <form action="{{ route('departement.pv.generate') }}" method="POST" class="grid grid-cols-2 gap-8">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Filière</label>
                        <select name="filiere_id" id="filiereSelect" class="w-full border-gray-200 bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Sélectionner</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id_Filiere }}">{{ $filiere->nom_Filiere }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Classe</label>
                        <select name="classe_id" id="classeSelect" class="w-full border-gray-200 bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required disabled>
                            <option value="">Sélectionner une filière d'abord</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Semestre & Année Académique</label>
                        <select name="semestre_id" class="w-full border-gray-200 bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Sélectionner</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id_Semestre }}">
                                    Semestre {{ $semestre->numero }} - {{ $semestre->anneeAcademique->libelle_Annee ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Type de Session</label>
                        <select name="session_type" class="w-full border-gray-200 bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="normale">Session Normale</option>
                            <option value="rattrapage">Session de Rattrapage</option>
                        </select>
                    </div>
                    <div class="col-span-3 flex gap-4 mt-6">
                        <button type="submit" name="preview" value="1" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition">
                            <i class="fas fa-eye mr-2"></i> Prévisualiser
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition">
                            <i class="fas fa-download mr-2"></i> Générer PDF
                        </button>
                    </div>
                </form>
                <script>
                    document.getElementById('filiereSelect').addEventListener('change', function() {
                        const filiereId = this.value;
                        const classeSelect = document.getElementById('classeSelect');

                        if (!filiereId) {
                            classeSelect.innerHTML = '<option value="">Sélectionner une filière d\'abord</option>';
                            classeSelect.disabled = true;
                            return;
                        }

                        fetch(`/departement/pv/classes?filiere_id=${filiereId}`)
                            .then(response => response.json())
                            .then(data => {
                                classeSelect.innerHTML = '<option value="">Sélectionner</option>';
                                data.forEach(classe => {
                                    const option = document.createElement('option');
                                    option.value = classe.id_Classe;
                                    option.textContent = classe.lib_Classe;
                                    classeSelect.appendChild(option);
                                });
                                classeSelect.disabled = false;
                            })
                            .catch(error => {
                                console.error('Erreur:', error);
                                classeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                            });
                    });
                </script>
            </div>
        </main>
    </div>
</body>
</html>
