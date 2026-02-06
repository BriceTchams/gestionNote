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
             <header class="flex justify-between items-center mb-8">
                <h2 class="text-lg font-semibold text-gray-700">Publication PV</h2>
                <div class="flex items-center gap-5">
                    <i class="far fa-bell text-gray-400"></i>
                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border shadow-sm">
                        <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white">DML</div>
                        <span class="text-sm font-medium">Dr. Martin Leroy</span>
                    </div>
                </div>
            </header>

            <h1 class="text-2xl font-bold text-gray-800">Publication des PV</h1>
            <p class="text-sm text-gray-400 mb-8">Gérez la publication des procès-verbaux de notes</p>

            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border flex items-center gap-4">
                    <div class="bg-green-100 text-green-600 w-12 h-12 rounded-xl flex items-center justify-center"><i class="fas fa-check"></i></div>
                    <div><p class="text-2xl font-bold">2</p><p class="text-xs text-gray-400">PV publiés</p></div>
                </div>
                <div class="bg-white p-6 rounded-2xl border flex items-center gap-4">
                    <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded-xl flex items-center justify-center"><i class="far fa-clock"></i></div>
                    <div><p class="text-2xl font-bold">2</p><p class="text-xs text-gray-400">En attente</p></div>
                </div>
                <div class="bg-white p-6 rounded-2xl border flex items-center gap-4">
                    <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-xl flex items-center justify-center"><i class="far fa-file-alt"></i></div>
                    <div><p class="text-2xl font-bold">4</p><p class="text-xs text-gray-400">Total PV</p></div>
                </div>
            </div>

            <div class="flex gap-2 mb-8 bg-white p-1 rounded-xl w-fit shadow-sm border">
                <button class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-medium text-sm">Publier un PV</button>
                <button class="text-gray-400 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium text-sm transition">Historique</button>
            </div>

            <div class="bg-white p-10 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-xl font-bold text-gray-800">Sélection</h3>
                <p class="text-sm text-gray-400 mb-8">Choisissez la classe et le semestre pour le PV</p>

                <form action="{{ route('departement.pv') }}" method="POST" class="grid grid-cols-3 gap-8">
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
                        <label class="text-sm font-bold text-gray-700">Semestre</label>
                        <select name="semestre_id" class="w-full border-gray-200 bg-gray-50 p-3 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Sélectionner</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id_Semestre }}">Semestre {{ $semestre->numero }}</option>
                            @endforeach
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