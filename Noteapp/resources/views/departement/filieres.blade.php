<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Filières & Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#fcfcfd] font-sans text-gray-900 no-scrollbar">

    <div class="flex flex-col md:flex-row min-h-screen">
        <div class="hidden md:block w-64 fixed h-full bg-white border-r z-40">
            @include('departement.sidebar')
        </div>

        <main class="flex-1 md:ml-64 p-4 md:p-8 w-full">

            <header class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
                <div class="space-y-1">
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Filières & Classes</h2>
                    <p class="text-gray-500 font-medium text-sm md:text-base">Architecture académique du département</p>
                </div>
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border">
                    <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">
                        {{ substr(auth()->guard('departement')->user()->chef_Departement ?? 'D', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->guard('departement')->user()->chef_Departement ?? 'Chef de département' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button onclick="toggleModal('modalFiliere')" class="bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-2xl flex items-center justify-center gap-2 hover:bg-gray-50 transition-all shadow-sm font-semibold text-sm">
                        <i class="fas fa-plus text-xs text-indigo-600"></i> Nouvelle Filière
                    </button>
                    <button onclick="openClasseModal()" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 font-semibold text-sm">
                        <i class="fas fa-plus text-xs"></i> Nouvelle Classe
                    </button>
                </div>
            </header>


            <div class="space-y-6">
                @foreach($filieres as $filiere)
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/40 transition-all duration-300 overflow-hidden group">
                    <div class="p-2">
                        <div class="w-full p-3 md:p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">

                            <div class="flex items-center gap-4 md:gap-5 cursor-pointer flex-1" onclick="toggleClasses({{ $filiere->id_Filiere }})">
                                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 text-white w-12 h-12 md:w-14 md:h-14 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200/50 flex-shrink-0">
                                    <i class="fas fa-sitemap text-lg md:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-lg md:text-xl font-bold text-gray-800 tracking-tight truncate">{{ $filiere->nom_Filiere }}</h3>
                                        <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[9px] font-bold uppercase tracking-widest border border-indigo-100/50 whitespace-nowrap">
                                            {{ $filiere->classes_count }} Niveaux
                                        </span>
                                    </div>
                                    <p class="hidden sm:block text-[11px] text-gray-400 mt-1 font-medium italic">Cliquez pour voir les classes</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-3 border-t md:border-t-0 pt-3 md:pt-0">
                                <div class="flex gap-1 pr-3 md:border-r border-gray-100">
                                    <button onclick="editFiliere({{ $filiere->id_Filiere }}, '{{ $filiere->nom_Filiere }}')" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="deleteFiliere({{ $filiere->id_Filiere }})" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </div>
                                <button onclick="toggleClasses({{ $filiere->id_Filiere }})" id="accordion-icon-{{ $filiere->id_Filiere }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="classes-{{ $filiere->id_Filiere }}" class="hidden bg-gray-50/30 border-t border-gray-50 transition-all">
                        <div class="p-4 md:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                            @foreach($filiere->classes as $classe)
                            <div class="bg-white p-5 rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all group/classe">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-black">
                                        {{ strtoupper(substr($classe->lib_Classe, 0, 2)) }}
                                    </div>
                                    <div class="flex gap-1 md:opacity-0 group-hover/classe:opacity-100 transition-opacity">
                                        <button onclick="editClasse({{ $classe->id_Classe }}, '{{ $classe->lib_Classe }}', {{ $filiere->id_Filiere }})" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteClasse({{ $classe->id_Classe }})" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                                <h4 class="font-bold text-gray-800 mb-1 truncate">{{ $classe->lib_Classe }}</h4>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full" style="width: 75%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-500 whitespace-nowrap">{{ $classe->nbre_Elv }} Étudiants</span>
                                </div>
                            </div>
                            @endforeach

                            <button onclick="openClasseModal({{ $filiere->id_Filiere }})" class="border-2 border-dashed border-gray-200 rounded-[1.5rem] p-6 flex flex-col items-center justify-center gap-3 text-gray-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-white transition-all min-h-[120px]">
                                <i class="fas fa-plus-circle text-xl"></i>
                                <span class="text-xs font-bold uppercase tracking-widest">Ajouter une classe</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </div>

    <div id="modalFiliere" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white w-full max-w-md rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-2xl my-auto">
            <h2 class="text-xl md:text-2xl font-black mb-6 md:mb-8 text-gray-900" id="modalFiliereTitle">Filière</h2>
            <form id="filiereForm" class="space-y-6">
                <input type="hidden" id="filiereId" name="id_Filiere">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Nom de la filière</label>
                    <input type="text" id="nomFiliere" name="nom_Filiere" placeholder="Ex: Génie Logiciel" class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" required>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="button" onclick="toggleModal('modalFiliere')" class="order-2 sm:order-1 flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">Annuler</button>
                    <button type="submit" class="order-1 sm:order-2 flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalClasse" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white w-full max-w-md rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-2xl my-auto">
            <h2 class="text-xl md:text-2xl font-black mb-6 md:mb-8 text-gray-900" id="modalClasseTitle">Classe</h2>
            <form id="classeForm" class="space-y-6">
                @csrf
                <input type="hidden" id="classeId" name="id_Classe">

                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Filière parente</label>
                    <select id="filiereIdClasse" name="filiere_id" class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none" required>
                        <option value="">Sélectionner une filière</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id_Filiere }}">{{ $filiere->nom_Filiere }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Nom de la classe</label>
                    <input type="text" id="libClasse" name="lib_Classe" placeholder="Ex: GL-L3-A" class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" required>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="button" onclick="toggleModal('modalClasse')" class="order-2 sm:order-1 flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">Annuler</button>
                    <button type="submit" class="order-1 sm:order-2 flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.toggle('hidden');
                document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
            }
        }

        function openClasseModal(filiereId = '') {
            // On s'assure que les éléments existent avant de les manipuler
            const title = document.getElementById('modalClasseTitle');
            const idInput = document.getElementById('classeId');
            const libInput = document.getElementById('libClasse');
            const filiereSelect = document.getElementById('filiereIdClasse');

            if(title) title.textContent = 'Nouvelle Classe';
            if(idInput) idInput.value = '';
            if(libInput) libInput.value = '';
            if(filiereSelect) filiereSelect.value = filiereId;

            toggleModal('modalClasse');
        }

        function toggleClasses(filiereId) {
            const container = document.getElementById(`classes-${filiereId}`);
            const icon = document.getElementById(`accordion-icon-${filiereId}`);
            if (!container) return;

            container.classList.toggle('hidden');
            if (icon) {
                icon.innerHTML = container.classList.contains('hidden')
                    ? '<i class="fas fa-chevron-down text-xs"></i>'
                    : '<i class="fas fa-chevron-up text-xs text-white"></i>';
                icon.classList.toggle('bg-indigo-600', !container.classList.contains('hidden'));
            }
        }

        function editFiliere(id, nom) {
            document.getElementById('modalFiliereTitle').textContent = 'Modifier Filière';
            document.getElementById('filiereId').value = id;
            document.getElementById('nomFiliere').value = nom;
            toggleModal('modalFiliere');
        }

        function editClasse(id, lib, filiereId) {
            document.getElementById('modalClasseTitle').textContent = 'Modifier Classe';
            document.getElementById('classeId').value = id;
            document.getElementById('libClasse').value = lib;
            document.getElementById('filiereIdClasse').value = filiereId;
            toggleModal('modalClasse');
        }

        // Formulaire Filière
        document.getElementById('filiereForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('filiereId').value;
            const url = id ? `/departement/filieres/${id}` : '/departement/filieres';
            const formData = new FormData(this);
            if(id) formData.append('_method', 'PUT');

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData
            }).then(res => res.ok ? location.reload() : alert("Erreur Filière"));
        });

        // Formulaire Classe
        document.getElementById('classeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('classeId').value;
            const url = id ? `/departement/filieres/classes/${id}` : '/departement/filieres/classes';
            const formData = new FormData(this);
            if(id) formData.append('_method', 'PUT');

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData
            }).then(res => {
                if(res.ok) location.reload();
                else {
                    res.json().then(data => alert("Erreur : " + (data.message || "Vérifiez vos champs")));
                }
            });
        });

        function deleteFiliere(id) {
            if (confirm('Supprimer cette filière et ses classes ?')) {
                fetch(`/departement/filieres/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                }).then(res => res.ok && location.reload());
            }
        }

        function deleteClasse(id) {
            if (confirm('Supprimer cette classe ?')) {
                fetch(`/departement/filieres/classes/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                }).then(res => res.ok && location.reload());
            }
        }
    </script>
</body>
</html>
