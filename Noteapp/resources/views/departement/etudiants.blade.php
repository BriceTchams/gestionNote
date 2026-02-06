<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Étudiants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] font-sans">
    <div class="flex">
    
       @include('departement.sidebar')

        <main class="ml-64 flex-1 p-8">
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Gestion des Étudiants</h2>
                    <p class="text-sm text-gray-400">Inscrivez et gérez les étudiants</p>
                </div>
                <div class="flex gap-3">
                    <button class="bg-white border text-gray-700 px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-gray-50 transition shadow-sm">
                        <i class="fas fa-upload text-xs"></i> Importer Excel
                    </button>
                    <button onclick="toggleModal('modalEtudiant')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                        <i class="fas fa-plus text-xs"></i> Inscrire un étudiant
                    </button>
                </div>
            </header>

            <div class="flex gap-4 mb-8">
                <div class="flex-1 bg-white border border-gray-200 rounded-2xl flex items-center px-4 py-3 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 transition">
                    <i class="fas fa-search text-gray-400 mr-3"></i>
                    <input type="text" id="searchInput" placeholder="Rechercher par nom, prénom, matricule ou login..." class="w-full outline-none text-sm bg-transparent">
                </div>
                <select id="filiereSelect" class="bg-white border border-gray-200 rounded-2xl px-6 py-3 text-sm text-gray-600 shadow-sm outline-none">
                    <option value="">Sélectionner une filière</option>
                    @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id_Filiere }}">{{ $filiere->nom_Filiere }}</option>
                    @endforeach
                </select>
                <select id="classeSelect" class="bg-white border border-gray-200 rounded-2xl px-6 py-3 text-sm text-gray-600 shadow-sm outline-none">
                    <option value="">Sélectionner une classe</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id_Classe }}">{{ $classe->lib_Classe }}</option>
                    @endforeach
                </select>
                <button id="afficherBtn" class="bg-indigo-600 text-white px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    <i class="fas fa-eye text-xs"></i> Afficher les étudiants
                </button>
            </div>

            {{-- <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border text-center shadow-sm">
                    <p class="text-3xl font-bold text-blue-600">8</p>
                    <p class="text-sm text-gray-400 mt-1">Étudiants inscrits</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border text-center shadow-sm">
                    <p class="text-3xl font-bold text-purple-600">5</p>
                    <p class="text-sm text-gray-400 mt-1">Classes</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border text-center shadow-sm">
                    <p class="text-3xl font-bold text-green-600">3</p>
                    <p class="text-sm text-gray-400 mt-1">Filières</p>
                </div>
            </div> --}}

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800">Liste des étudiants</h3>
                        <p class="text-xs text-gray-400" id="countText">0 étudiant(s) trouvé(s)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="refreshBtn" class="text-gray-400 hover:text-indigo-600 p-2">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table id="etudiantsTable" class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] tracking-widest font-bold">
                            <tr>
                                <th class="p-4 pl-8">Étudiant</th>
                                <th class="p-4 text-center">Matricule</th>
                                <th class="p-4 text-center">Login</th>
                                <th class="p-4 text-center">Filière</th>
                                <th class="p-4 text-center">Classe</th>
                                <th class="p-4 pr-8 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="etudiantsBody" class="divide-y text-sm">
                            @foreach($etudiants as $etudiant)
                                <tr data-id="{{ $etudiant->id_Etudiant }}" class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 pl-8">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $etudiant->nom }}</p>
                                                <p class="text-xs text-gray-400">{{ $etudiant->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center"><span class="bg-gray-100 px-3 py-1 rounded-lg font-mono text-xs font-bold text-gray-600">{{ $etudiant->matricule }}</span></td>
                                    <td class="p-4 text-center"><span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg font-mono text-xs font-bold">{{ $etudiant->login }}</span></td>
                                    <td class="p-4 text-center text-gray-600">
                                        @if($etudiant->classe && $etudiant->classe->filiere)
                                            {{ $etudiant->classe->filiere->nom_Filiere }}
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($etudiant->classe)
                                            <span class="bg-indigo-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">{{ $etudiant->classe->lib_Classe }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 pr-8 text-right space-x-2">
                                        <button onclick="editEtudiant({{ $etudiant->id_Etudiant }})" class="text-gray-400 hover:text-indigo-600"><i class="fas fa-pen"></i></button>
                                        <button onclick="deleteEtudiant({{ $etudiant->id_Etudiant }})" class="text-gray-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="modalEtudiant" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-[500px] rounded-[2rem] p-8 shadow-2xl">
            <h2 class="text-xl font-bold mb-1 text-gray-800">Inscrire un étudiant</h2>
            <p class="text-sm text-gray-400 mb-6">Remplissez les informations de l'étudiant</p>
            <form class="space-y-4" action="POST" action="">
                @crsf
                <div class="grid grid-cols-2 gap-4">
                  
                
                    
                    <div><label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nom complet</label><input type="text" class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" name="nom"></div>
                </div>
                <div><label class="block text-xs font-bold uppercase text-gray-400 mb-1">Email </label><input type="email" class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" name="email"></div>

                    <div><label class="block text-xs font-bold uppercase text-gray-400 mb-1">Telephone</label><input type="tel" class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" name="telephone"></div>

                     <div><label class="block text-xs font-bold uppercase text-gray-400 mb-1">Date naissance</label><input type="date" class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" name="dateNais"></div>

                <div><label class="block text-xs font-bold uppercase text-gray-400 mb-1">Classe</label>
                    <select class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" name="idClasse">
                        <option name="idClasse">Sélectionner une classe</option>
                        <option name="idClasse">GL-L3-A</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="toggleModal('modalEtudiant')" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm">Annuler</button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-200">Inscrire l'étudiant</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const filiereSelect = document.getElementById('filiereSelect');
            const classeSelect = document.getElementById('classeSelect');
            const afficherBtn = document.getElementById('afficherBtn');
            const searchInput = document.getElementById('searchInput');
            const refreshBtn = document.getElementById('refreshBtn');
            
            filiereSelect.addEventListener('change', function() {
                const filiereId = this.value;
                if (filiereId) {
                    fetch(`/departement/filieres/${filiereId}/classes`)
                        .then(response => response.json())
                        .then(data => {
                            classeSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                            data.classes.forEach(classe => {
                                const option = document.createElement('option');
                                option.value = classe.id_Classe;
                                option.textContent = classe.lib_Classe;
                                classeSelect.appendChild(option);
                            });
                        });
                } else {
                    classeSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                }
            });
            
            afficherBtn.addEventListener('click', function() {
                const classeId = classeSelect.value;
                if (!classeId) {
                    alert('Veuillez sélectionner une classe');
                    return;
                }
                
                fetch(`/departement/etudiants/by-classe?classe_id=${classeId}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data.etudiants);
                    });
            });
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value;
                const classeId = classeSelect.value;
                
                fetch(`/departement/etudiants/search?search=${encodeURIComponent(searchTerm)}&classe_id=${classeId}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data.etudiants);
                    });
            });
            
            refreshBtn.addEventListener('click', function() {
                const classeId = classeSelect.value;
                if (classeId) {
                    fetch(`/departement/etudiants/by-classe?classe_id=${classeId}`)
                        .then(response => response.json())
                        .then(data => {
                            updateTable(data.etudiants);
                        });
                }
            });
        });
        
        function updateTable(etudiants) {
            const tbody = document.getElementById('etudiantsBody');
            const countText = document.getElementById('countText');
            
            tbody.innerHTML = '';
            
            if (etudiants.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-gray-400">Aucun étudiant trouvé</td></tr>';
                countText.textContent = '0 étudiant(s) trouvé(s)';
                return;
            }
            
            etudiants.forEach(etudiant => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50/50 transition';
                row.dataset.id = etudiant.id_Etudiant;
                
                row.innerHTML = `
                    <td class="p-4 pl-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">${etudiant.nom}</p>
                                <p class="text-xs text-gray-400">${etudiant.email}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-center"><span class="bg-gray-100 px-3 py-1 rounded-lg font-mono text-xs font-bold text-gray-600">${etudiant.matricule}</span></td>
                    <td class="p-4 text-center"><span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg font-mono text-xs font-bold">${etudiant.login}</span></td>
                    <td class="p-4 text-center text-gray-600">
                        ${etudiant.classe && etudiant.classe.filiere ? etudiant.classe.filiere.nom_Filiere : ''}
                    </td>
                    <td class="p-4 text-center">
                        ${etudiant.classe ? `<span class="bg-indigo-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">${etudiant.classe.lib_Classe}</span>` : ''}
                    </td>
                    <td class="p-
</body>
</html>