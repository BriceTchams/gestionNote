<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniNotes - UE & Groupes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">UE & Groupes</h2>
                    <p class="text-gray-500 font-medium text-sm md:text-base">Gestion des Unités d'Enseignement</p>
                </div>
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border">
                    <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">
                        {{ substr(auth()->guard('departement')->user()->chef_Departement ?? 'D', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->guard('departement')->user()->chef_Departement ?? 'Chef de département' }}</span>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="openModal('modalGroupe')" class="bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-2xl flex items-center justify-center gap-2 hover:bg-gray-50 transition-all shadow-sm font-semibold text-sm">
                        <i class="fas fa-layer-group text-xs text-indigo-600"></i> Créer un groupe
                    </button>
                    <button onclick="openModal('modalUe')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 font-semibold text-sm">
                        <i class="fas fa-plus text-xs"></i> Ajouter une UE
                    </button>
                </div>
            </header>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm mb-10">
                <div class="flex flex-col md:flex-row items-end gap-6">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Sélectionner une filière</label>
                        <select id="filiereSelect" class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-gray-700">
                            <option value="">-- Choisir une filière --</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id_Filiere }}" {{ request('filiere_id') == $filiere->id_Filiere ? 'selected' : '' }}>
                                    {{ $filiere->nom_Filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button id="afficherBtn" class="bg-gray-900 text-white px-10 py-4 rounded-2xl font-bold hover:bg-black transition-all shadow-lg shadow-gray-200 w-full md:w-auto">
                        Afficher
                    </button>
                </div>
            </div>

            <div id="contentArea">
                @if(request('filiere_id'))
                    <div class="space-y-8">
                        @forelse($groupes as $groupe)
                            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                                <div class="p-6 md:p-8 bg-gray-50/50 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 cursor-pointer hover:bg-gray-100/50 transition-colors" onclick="toggleGroup({{ $groupe->idGroupe }})">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-gray-900 leading-tight">{{ $groupe->intitule }}</h3>
                                            <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">{{ $groupe->code }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="bg-white px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-500">
                                            {{ $groupe->ues->count() }} UE(s) associée(s)
                                        </span>
                                        <div id="icon-{{ $groupe->idGroupe }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 transition-transform duration-300">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                <div id="group-{{ $groupe->idGroupe }}" class="hidden overflow-x-auto border-t border-gray-50">
                                    <table class="w-full text-left">
                                        <thead class="bg-white text-gray-400 uppercase text-[10px] tracking-[0.2em] font-black">
                                            <tr>
                                                <th class="p-6 pl-10">UE</th>
                                                <th class="p-6">Crédits</th>
                                                <th class="p-6">Enseignant</th>
                                                <th class="p-6 pr-10 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($groupe->ues as $ue)
                                                <tr class="hover:bg-indigo-50/30 transition-all">
                                                    <td class="p-6 pl-10">
                                                        <div>
                                                            <p class="font-bold text-gray-900 text-base leading-snug">{{ $ue->libelle }}</p>
                                                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-tighter">{{ $ue->code }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="p-6">
                                                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg font-black text-xs">
                                                            {{ $ue->credits }} CR
                                                        </span>
                                                    </td>
                                                    <td class="p-6">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-500">
                                                                {{ substr($ue->enseignant->nom_Enseignant ?? '?', 0, 1) }}
                                                            </div>
                                                            <span class="text-sm font-semibold text-gray-600">{{ $ue->enseignant->nom_Enseignant ?? 'Non assigné' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-6 pr-10 text-right">
                                                        <button class="text-gray-400 hover:text-indigo-600 transition-all">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 flex flex-col items-center justify-center text-gray-400 bg-white rounded-[2rem] border border-dashed border-gray-200">
                                <i class="fas fa-layer-group text-4xl mb-4 opacity-20"></i>
                                <p class="italic font-medium">Aucun groupe trouvé pour cette filière.</p>
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="py-32 flex flex-col items-center justify-center text-gray-400 bg-white rounded-[3rem] border border-gray-100 shadow-sm">
                        <div class="w-24 h-24 bg-indigo-50 text-indigo-200 rounded-full flex items-center justify-center text-4xl mb-6 shadow-inner">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-2">Prêt à commencer ?</h3>
                        <p class="text-sm font-medium text-gray-400 max-w-xs text-center">Sélectionnez une filière ci-dessus pour afficher les groupes et les unités d'enseignement associés.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal Groupe -->
    <div id="modalGroupe" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl">
            <h2 class="text-2xl font-black mb-2 text-gray-900">Nouveau Groupe</h2>
            <p class="text-sm text-gray-400 mb-8 font-medium">Créez un regroupement pédagogique d'UE.</p>
            <form action="{{ route('departement.groupes.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Filière</label>
                    <select name="id_Filiere" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                        @foreach($filieres as $f)
                            <option value="{{ $f->id_Filiere }}">{{ $f->nom_Filiere }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Code du Groupe</label>
                    <input type="text" name="code" placeholder="ex: INF_BASE" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Intitulé</label>
                    <input type="text" name="intitule" placeholder="ex: Informatique Fondamentale" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('modalGroupe')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">Annuler</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal UE -->
    <div id="modalUe" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl">
            <h2 class="text-2xl font-black mb-2 text-gray-900">Nouvelle UE</h2>
            <p class="text-sm text-gray-400 mb-8 font-medium">Ajoutez une unité d'enseignement à un groupe.</p>
            <form action="{{ route('departement.ues.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Code</label>
                        <input type="text" name="code" placeholder="INF301" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Crédits</label>
                        <input type="number" name="credits" placeholder="4" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Libellé</label>
                    <input type="text" name="libelle" placeholder="Programmation Orientée Objet" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Enseignant Responsable</label>
                    <select name="id_Enseignant" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                        @foreach($enseignants as $e)
                            <option value="{{ $e->id_Enseignant }}">{{ $e->nom_Enseignant }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Groupe d'UE</label>
                    <select name="idGroupe" required class="w-full bg-gray-50 border-none p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                        @foreach($groupes_all as $g)
                            <option value="{{ $g->idGroupe }}">{{ $g->intitule }} ({{ $g->filiere->nom_Filiere ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('modalUe')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">Annuler</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function toggleGroup(id) {
            const content = document.getElementById(`group-${id}`);
            const icon = document.getElementById(`icon-${id}`);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                icon.classList.add('text-indigo-600', 'border-indigo-200', 'bg-indigo-50');
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                icon.classList.remove('text-indigo-600', 'border-indigo-200', 'bg-indigo-50');
            }
        }

        document.getElementById('afficherBtn').addEventListener('click', function() {
            const filiereId = document.getElementById('filiereSelect').value;
            if (filiereId) {
                window.location.href = `?filiere_id=${filiereId}`;
            } else {
                alert('Veuillez sélectionner une filière');
            }
        });
    </script>
</body>
</html>
