<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border">
                    <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">
                        {{ substr(auth()->guard('departement')->user()->chef_Departement ?? 'D', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->guard('departement')->user()->chef_Departement ?? 'Chef de département' }}</span>
                </div>
                <div class="flex gap-3">
                    <button class="bg-white border text-gray-700 px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-gray-50 transition shadow-sm">
                        <i class="fas fa-upload text-xs"></i> Importer Excel
                    </button>
                    <a id="downloadPdfBtn" href="{{ route('departement.etudiants.download') }}" class="bg-white border text-red-600 px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-red-50 transition shadow-sm">
                        <i class="fas fa-file-pdf text-xs"></i> Télécharger PDF
                    </a>
                    <button onclick="toggleModal('modalEtudiant')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                        <i class="fas fa-plus text-xs"></i> Inscrire un étudiant
                    </button>
                </div>
            </header>

            {{-- Messages de succès --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-bold">{{ session('success') }}</p>
                        @if(session('generated_login'))
                            <div class="mt-3 bg-white p-4 rounded-lg border border-green-200">
                                <p class="text-sm mb-2"><strong>Informations de connexion générées :</strong></p>
                                <p class="text-sm">📧 <strong>Login :</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ session('generated_login') }}</code></p>
                                <p class="text-sm">🔑 <strong>Mot de passe :</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ session('generated_password') }}</code></p>
                                @if(session('generated_matricule'))
                                    <p class="text-sm">🎓 <strong>Matricule :</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ session('generated_matricule') }}</code></p>
                                @endif
                                <p class="text-xs text-green-600 mt-2">⚠️ Veuillez noter ces informations, elles ne seront plus affichées.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6">
                    <p class="font-bold mb-2">Erreurs de validation :</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                    <i class="fas fa-eye text-xs"></i> Afficher
                </button>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800">Liste des étudiants</h3>
                        <p class="text-xs text-gray-400" id="countText">
                            @if(count($etudiants) > 0)
                                {{ count($etudiants) }} étudiant(s) trouvé(s)
                            @else
                                Sélectionnez une classe et cliquez sur "Afficher"
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="refreshBtn" class="text-gray-400 hover:text-indigo-600 p-2" title="Actualiser">
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
                                <th class="p-4 text-center">Password</th>
                                <th class="p-4 text-center">Filière</th>
                                <th class="p-4 pr-8 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="etudiantsBody" class="divide-y text-sm">
                            @forelse($etudiants as $etudiant)
                                <tr data-id="{{ $etudiant->id_Etudiant }}" class="hover:bg-gray-50/50 transition cursor-pointer group" onclick="toggleDetails({{ $etudiant->id_Etudiant }})">
                                    <td class="p-4 pl-8">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center font-bold">
                                                {{ substr($etudiant->nom_Complet ?? $etudiant->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $etudiant->nom }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Cliquez pour voir les détails</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center"><span class="bg-gray-100 px-3 py-1 rounded-lg font-mono text-xs font-bold text-gray-600">{{ $etudiant->matricule }}</span></td>
                                    <td class="p-4 text-center"><span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg font-mono text-xs font-bold">{{ $etudiant->login }}</span></td>
                                    <td class="p-4 text-center"><span class="bg-red-100 text-red-600 px-3 py-1 rounded-lg font-mono text-xs font-bold">{{ $etudiant->add_plain_password }}</span></td>
                                    <td class="p-4 text-center text-gray-600">
                                        @if($etudiant->classe && $etudiant->classe->filiere)
                                            {{ $etudiant->classe->filiere->nom_Filiere }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 pr-8 text-right space-x-2" onclick="event.stopPropagation()">
                                        <button onclick="editEtudiant({{ $etudiant->id_Etudiant }})" class="text-gray-400 hover:text-indigo-600 transition" title="Modifier">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button onclick="deleteEtudiant({{ $etudiant->id_Etudiant }})" class="text-gray-400 hover:text-red-500 transition" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr id="details-{{ $etudiant->id_Etudiant }}" class="hidden bg-gray-50/50">
                                    <td colspan="6" class="p-6">
                                        <div class="bg-white rounded-2xl border p-4 shadow-sm">
                                            <h4 class="text-xs font-bold uppercase text-gray-400 mb-4 flex items-center gap-2">
                                                <i class="fas fa-info-circle text-purple-500"></i>
                                                Détails de l'étudiant : {{ $etudiant->nom }}
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div class="space-y-2">
                                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Contact</p>
                                                    <p class="text-sm"><strong>Email:</strong> {{ $etudiant->email }}</p>
                                                    <p class="text-sm"><strong>Téléphone:</strong> {{ $etudiant->telephone }}</p>
                                                </div>
                                                <div class="space-y-2">
                                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Informations Académiques</p>
                                                    <p class="text-sm"><strong>Matricule:</strong> {{ $etudiant->matricule }}</p>
                                                    <p class="text-sm"><strong>Classe:</strong> {{ $etudiant->classe->lib_Classe ?? 'N/A' }}</p>
                                                </div>
                                                <div class="space-y-2">
                                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">État Civil</p>
                                                    <p class="text-sm"><strong>Date de naissance:</strong> {{ $etudiant->date_Naissance ? \Carbon\Carbon::parse($etudiant->date_Naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400">
                                        <i class="fas fa-users text-4xl mb-3 opacity-30"></i>
                                        <p>Aucun étudiant trouvé. Sélectionnez une classe pour afficher les étudiants.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal d'ajout d'étudiant --}}
    <div id="modalEtudiant" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-[500px] rounded-[2rem] p-8 shadow-2xl">
            <h2 class="text-xl font-bold mb-1 text-gray-800">Inscrire un étudiant</h2>
            <p class="text-sm text-gray-400 mb-6">Remplissez les informations de l'étudiant</p>
            <form method="POST" action="{{ route('departement.etudiants.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nom complet *</label>
                    <input type="text" name="nom" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Ex: KAMDEM Jean Pierre">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Email *</label>
                    <input type="email" name="email" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Ex: jean.kamdem@example.com">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Téléphone *</label>
                    <input type="tel" name="telephone" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Ex: 237699123456">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Date de naissance</label>
                    <input type="date" name="dateNais" class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <p class="text-xs text-gray-400 italic mt-2">Le matricule, le login et le mot de passe seront générés automatiquement.</p>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Classe *</label>
                    <select name="idClasse" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="" >Sélectionner une classe</option>
                        @foreach($filieres as $filiere)
                            @if($filiere->classes && count($filiere->classes) > 0)
                                <optgroup label="{{ $filiere->nom_Filiere }}">
                                    @foreach($filiere->classes as $classe)
                                        <option value="{{ $classe->id_Classe }}">{{ $classe->lib_Classe }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="toggleModal('modalEtudiant')" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Annuler</button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Inscrire l'étudiant</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de modification d'étudiant --}}
    <div id="modalEditEtudiant" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-[500px] rounded-[2rem] p-8 shadow-2xl">
            <h2 class="text-xl font-bold mb-1 text-gray-800">Modifier un étudiant</h2>
            <p class="text-sm text-gray-400 mb-6">Modifiez les informations de l'étudiant</p>
            <form id="editForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nom complet *</label>
                    <input type="text" id="edit_nom" name="nom" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Email *</label>
                    <input type="email" id="edit_email" name="email" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Téléphone *</label>
                    <input type="tel" id="edit_telephone" name="telephone" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Date de naissance</label>
                    <input type="date" id="edit_dateNais" name="dateNais" class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Classe *</label>
                    <select id="edit_idClasse" name="idClasse" required class="w-full border p-3 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">Sélectionner une classe</option>
                        @foreach($filieres as $filiere)
                            @if($filiere->classes && count($filiere->classes) > 0)
                                <optgroup label="{{ $filiere->nom_Filiere }}">
                                    @foreach($filiere->classes as $classe)
                                        <option value="{{ $classe->id_Classe }}">{{ $classe->lib_Classe }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="toggleModal('modalEditEtudiant')" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Annuler</button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Configuration CSRF token pour toutes les requêtes AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function toggleDetails(id) {
            const detailRow = document.getElementById(`details-${id}`);
            if (detailRow) {
                detailRow.classList.toggle('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const filiereSelect = document.getElementById('filiereSelect');
            const classeSelect = document.getElementById('classeSelect');
            const afficherBtn = document.getElementById('afficherBtn');
            const searchInput = document.getElementById('searchInput');
            const refreshBtn = document.getElementById('refreshBtn');

            // Charger les classes quand une filière est sélectionnée
            filiereSelect.addEventListener('change', function() {
                const filiereId = this.value;
                if (filiereId) {
                    fetch(`/departement/filieres/${filiereId}/classes`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            classeSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                            data.classes.forEach(classe => {
                                const option = document.createElement('option');
                                option.value = classe.id_Classe;
                                option.textContent = classe.lib_Classe;
                                classeSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Erreur lors du chargement des classes');
                        });
                } else {
                    classeSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                }
            });

            // Afficher les étudiants d'une classe
            afficherBtn.addEventListener('click', function() {
                const classeId = classeSelect.value;
                if (!classeId) {
                    alert('Veuillez sélectionner une classe');
                    return;
                }

                fetch(`/departement/etudiants/by-classe?classe_id=${classeId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Données reçues:', data);
                        updateTable(data.etudiants);
                    })
                    .catch(error => {
                        console.error('Erreur détaillée:', error);
                        alert('Erreur lors du chargement des étudiants: ' + error.message);
                    });
            });

            // Recherche en temps réel
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value;
                    const classeId = classeSelect.value;

                    fetch(`/departement/etudiants/search?search=${encodeURIComponent(searchTerm)}&classe_id=${classeId}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            updateTable(data.etudiants);
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                        });
                }, 300);
            });

            // Bouton actualiser
            refreshBtn.addEventListener('click', function() {
                const classeId = classeSelect.value;
                if (classeId) {
                    fetch(`/departement/etudiants/by-classe?classe_id=${classeId}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            updateTable(data.etudiants);
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                        });
                } else {
                    location.reload();
                }
            });

            // Mise à jour du lien de téléchargement PDF
            const downloadPdfBtn = document.getElementById('downloadPdfBtn');
            function updatePdfLink() {
                const classeId = classeSelect.value;
                const search = searchInput.value;
                let url = "{{ route('departement.etudiants.download') }}";
                const params = new URLSearchParams();
                if (classeId) params.append('classe_id', classeId);
                if (search) params.append('search', search);

                if (params.toString()) {
                    url += '?' + params.toString();
                }
                downloadPdfBtn.href = url;
            }

            classeSelect.addEventListener('change', updatePdfLink);
            searchInput.addEventListener('input', updatePdfLink);
        });

        // Mettre à jour le tableau
        function updateTable(etudiants) {
            const tbody = document.getElementById('etudiantsBody');
            const countText = document.getElementById('countText');

            tbody.innerHTML = '';
            countText.textContent = `${etudiants.length} étudiant(s) trouvé(s)`;

            if (etudiants.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <i class="fas fa-users text-4xl mb-3 opacity-30"></i>
                            <p>Aucun étudiant trouvé</p>
                        </td>
                    </tr>
                `;
                return;
            }

            etudiants.forEach(etudiant => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50/50 transition cursor-pointer group';
                row.dataset.id = etudiant.id_Etudiant;
                row.onclick = () => toggleDetails(etudiant.id_Etudiant);

                const nom = etudiant.nom || etudiant.nom_Complet || 'N/A';
                const matricule = etudiant.matricule || etudiant.matricule_Et || 'N/A';
                const email = etudiant.email || 'N/A';
                const telephone = etudiant.telephone || 'N/A';
                const dateNais = etudiant.date_Naissance || 'Non renseignée';
                const login = etudiant.login || 'N/A';
                const password = etudiant.add_plain_password || 'N/A';
                const filiere = etudiant.classe && etudiant.classe.filiere ? etudiant.classe.filiere.nom_Filiere : '-';
                const classeNom = etudiant.classe ? etudiant.classe.lib_Classe : 'N/A';

                row.innerHTML = `
                    <td class="p-4 pl-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center font-bold">
                                ${nom.charAt(0)}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">${nom}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Cliquez pour voir les détails</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-center"><span class="bg-gray-100 px-3 py-1 rounded-lg font-mono text-xs font-bold text-gray-600">${matricule}</span></td>
                    <td class="p-4 text-center"><span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg font-mono text-xs font-bold">${login}</span></td>
                    <td class="p-4 text-center"><span class="bg-red-100 text-red-600 px-3 py-1 rounded-lg font-mono text-xs font-bold">${password}</span></td>
                    <td class="p-4 text-center text-gray-600">${filiere}</td>
                    <td class="p-4 pr-8 text-right space-x-2" onclick="event.stopPropagation()">
                        <button onclick="editEtudiant(${etudiant.id_Etudiant})" class="text-gray-400 hover:text-indigo-600 transition" title="Modifier">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button onclick="deleteEtudiant(${etudiant.id_Etudiant})" class="text-gray-400 hover:text-red-500 transition" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                const detailRow = document.createElement('tr');
                detailRow.id = `details-${etudiant.id_Etudiant}`;
                detailRow.className = 'hidden bg-gray-50/50';
                detailRow.innerHTML = `
                    <td colspan="6" class="p-6">
                        <div class="bg-white rounded-2xl border p-4 shadow-sm">
                            <h4 class="text-xs font-bold uppercase text-gray-400 mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-purple-500"></i>
                                Détails de l'étudiant : ${nom}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Contact</p>
                                    <p class="text-sm"><strong>Email:</strong> ${email}</p>
                                    <p class="text-sm"><strong>Téléphone:</strong> ${telephone}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Informations Académiques</p>
                                    <p class="text-sm"><strong>Matricule:</strong> ${matricule}</p>
                                    <p class="text-sm"><strong>Classe:</strong> ${classeNom}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">État Civil</p>
                                    <p class="text-sm"><strong>Date de naissance:</strong> ${dateNais}</p>
                                </div>
                            </div>
                        </div>
                    </td>
                `;

                tbody.appendChild(row);
                tbody.appendChild(detailRow);
            });
        }

        // Modifier un étudiant
        function editEtudiant(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            fetch(`/departement/etudiants/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(etudiant => {
                    document.getElementById('edit_id').value = etudiant.id_Etudiant;
                    document.getElementById('edit_nom').value = etudiant.nom || etudiant.nom_Complet;
                    document.getElementById('edit_email').value = etudiant.email;
                    document.getElementById('edit_telephone').value = etudiant.telephone;
                    document.getElementById('edit_dateNais').value = etudiant.date_naissance || etudiant.date_Naissance || '';
                    document.getElementById('edit_idClasse').value = etudiant.id_Classe;

                    toggleModal('modalEditEtudiant');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement des données de l\'étudiant');
                });
        }

        // Formulaire de modification
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch(`/departement/etudiants/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        toggleModal('modalEditEtudiant');
                        location.reload(); // Recharger la page pour voir les modifications
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la modification de l\'étudiant');
                });
        });

        // Supprimer un étudiant
        function deleteEtudiant(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
                return;
            }

            fetch(`/departement/etudiants/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        // Supprimer la ligne du tableau
                        document.querySelector(`tr[data-id="${id}"]`).remove();
                        // Mettre à jour le compteur
                        const tbody = document.getElementById('etudiantsBody');
                        const count = tbody.querySelectorAll('tr').length;
                        document.getElementById('countText').textContent = `${count} étudiant(s) trouvé(s)`;
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression de l\'étudiant');
                });
        }
    </script>
</body>
</html>
