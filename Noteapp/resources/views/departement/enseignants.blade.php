<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Enseignants</title>
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
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Gestion des Enseignants</h2>
                    <p class="text-gray-500 font-medium text-sm md:text-base">{{ $departement->nom_Departement ?? 'Département' }}</p>
                </div>
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border">
                    <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white font-bold">
                        {{ substr(auth()->guard('departement')->user()->chef_Departement ?? 'D', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->guard('departement')->user()->chef_Departement ?? 'Chef de département' }}</span>
                </div>
                <button onclick="document.getElementById('modalAdd').classList.remove('hidden')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl flex items-center justify-center gap-2 hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 font-semibold text-sm">
                    <i class="fas fa-plus text-xs"></i> Ajouter un enseignant
                </button>
            </header>

            <div class="bg-white border border-gray-100 rounded-[2rem] flex items-center px-6 py-4 shadow-sm mb-10 focus-within:ring-2 focus-within:ring-indigo-500 transition-all">
                <i class="fas fa-search text-gray-400 mr-4"></i>
                <input type="text" id="searchInput" placeholder="Rechercher par nom, email, matricule ou login..." class="w-full outline-none text-sm bg-transparent">
            </div>

            @if(session('generated_login'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-900 p-8 rounded-[2rem] mb-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/50 rounded-full -mr-16 -mt-16"></div>
                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-black tracking-tight mb-2">Accès Enseignant Généré</h4>
                        <p class="text-sm text-emerald-700/80 font-medium mb-4">Ces informations sont confidentielles et ne seront plus affichées.</p>
                        <div class="flex flex-wrap gap-4">
                            <div class="bg-white/60 px-4 py-2 rounded-xl border border-emerald-200/50">
                                <span class="text-[10px] uppercase font-black text-emerald-500 block">Matricule</span>
                                <span class="font-bold font-mono">{{ session('generated_matricule') }}</span>
                            </div>
                            <div class="bg-white/60 px-4 py-2 rounded-xl border border-emerald-200/50">
                                <span class="text-[10px] uppercase font-black text-emerald-500 block">Login</span>
                                <span class="font-bold font-mono">{{ session('generated_login') }}</span>
                            </div>
                            <div class="bg-white/60 px-4 py-2 rounded-xl border border-emerald-200/50">
                                <span class="text-[10px] uppercase font-black text-emerald-500 block">Mot de passe</span>
                                <span class="font-bold font-mono text-indigo-600">{{ session('generated_password') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="bg-white/40 hover:bg-white/80 w-10 h-10 rounded-full flex items-center justify-center transition-all">
                    <i class="fas fa-times text-emerald-900"></i>
                </button>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="bg-indigo-50 text-indigo-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $stats['count'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Enseignants</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                    <div class="bg-purple-50 text-purple-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $stats['total_ues'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">UEs attribuées</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5 sm:col-span-2 lg:col-span-1">
                    <div class="bg-emerald-50 text-emerald-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $stats['avg_ues'] }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Moy. UEs/Ens.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-12">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Liste des enseignants</h3>
                        <p class="text-xs font-medium text-gray-400 mt-1">{{ $enseignants->count() }} enseignant(s) actif(s)</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-gray-400 uppercase text-[10px] tracking-[0.2em] font-black">
                            <tr>
                                <th class="p-6 pl-10">Enseignant</th>
                                <th class="p-6 text-center">Identifiants</th>
                                <th class="p-6">Contact</th>
                                <th class="p-6">Matricule</th>
                                <th class="p-6 pr-10 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm" id="enseignantTable">
                            @foreach($enseignants as $enseignant)
                            <tr class="hover:bg-indigo-50/30 transition-all cursor-pointer group border-b border-gray-50" onclick="toggleUes({{ $enseignant->id_Enseignant }})">
                                <td class="p-6 pl-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-indigo-100">
                                            {{ substr($enseignant->nom_Enseignant, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-base">{{ $enseignant->nom_Enseignant }}</p>
                                            <p class="text-[10px] text-indigo-500 uppercase tracking-widest font-black mt-0.5 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                                Voir les UEs <i id="chevron-{{ $enseignant->id_Enseignant }}" class="fas fa-chevron-right text-[8px] transition-transform duration-300"></i>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-xl font-mono text-[11px] font-bold border border-blue-100/50 w-full text-center">
                                            <i class="fas fa-user-circle mr-1.5 opacity-50"></i>{{ $enseignant->login }}
                                        </span>
                                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl font-mono text-[11px] font-bold border border-emerald-100/50 w-full text-center">
                                            <i class="fas fa-lock mr-1.5 opacity-50"></i>{{ $enseignant->add_plain_password }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-2 text-gray-500">
                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-white transition-colors">
                                            <i class="far fa-envelope text-xs"></i>
                                        </div>
                                        <span class="font-medium">{{ $enseignant->email }}</span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg font-bold text-xs">{{ $enseignant->matricule }}</span>
                                </td>
                                <td class="p-6 pr-10 text-right space-x-1" onclick="event.stopPropagation()">
                                    <button onclick="editEnseignant({{ json_encode($enseignant) }})" class="w-10 h-10 rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <form action="{{ route('departement.enseignants.destroy', $enseignant->id_Enseignant) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-10 h-10 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all" onclick="return confirm('Supprimer cet enseignant ?')">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr id="ues-{{ $enseignant->id_Enseignant }}" class="hidden">
                                <td colspan="5" class="p-0">
                                    <div class="bg-gray-50/80 px-10 py-8 border-t border-b border-gray-100">
                                        <div class="flex items-center justify-between mb-6">
                                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 flex items-center gap-3">
                                                <span class="w-8 h-px bg-gray-200"></span>
                                                Charge pédagogique
                                                <span class="w-8 h-px bg-gray-200"></span>
                                            </h4>
                                            <span class="bg-white px-4 py-1.5 rounded-full border border-gray-200 text-[11px] font-bold text-gray-500 shadow-sm">
                                                {{ $enseignant->ues->count() }} UE(s) au total
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @forelse($enseignant->ues as $ue)
                                                <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all flex flex-col justify-between group/ue">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-black">
                                                            {{ $ue->code }}
                                                        </div>
                                                        <span class="text-[10px] bg-indigo-600 text-white px-2.5 py-1 rounded-lg font-black uppercase tracking-widest">
                                                            {{ $ue->credits }} CR
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-800 text-sm mb-1 line-clamp-1">{{ $ue->libelle }}</p>
                                                        @if($ue->groupe_ue && $ue->groupe_ue->filiere)
                                                            <div class="flex items-center gap-1.5">
                                                                <i class="fas fa-graduation-cap text-[10px] text-gray-300"></i>
                                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $ue->groupe_ue->filiere->nom_Filiere }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-span-full py-12 flex flex-col items-center justify-center text-gray-400 bg-white rounded-3xl border border-dashed border-gray-200">
                                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fas fa-book-open text-2xl opacity-20"></i>
                                                    </div>
                                                    <p class="italic font-medium text-sm">Aucune UE n'est actuellement attribuée.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="h-4 bg-transparent"><td colspan="5"></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Ajout -->
    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
            <h2 class="text-2xl font-black mb-2 text-gray-900">Nouvel Enseignant</h2>
            <p class="text-sm text-gray-400 mb-8 font-medium">Enregistrez un nouveau membre du corps professoral.</p>

            <form action="{{ route('departement.enseignants.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Nom complet</label>
                    <input type="text" name="nom_Enseignant" required placeholder="Ex: Pr. Jean Dupont" class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Adresse Email</label>
                    <input type="email" name="email" required placeholder="Ex: jean.dupont@universite.com" class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100/50">
                    <p class="text-[11px] text-indigo-600 font-bold leading-relaxed italic flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Le matricule, le login et le mot de passe seront générés automatiquement par le système.
                    </p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('modalAdd').classList.add('hidden')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">Annuler</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
            <h2 class="text-2xl font-black mb-2 text-gray-900">Modifier l'Enseignant</h2>
            <p class="text-sm text-gray-400 mb-8 font-medium">Mise à jour des informations de l'enseignant.</p>

            <form id="editForm" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Nom complet</label>
                    <input type="text" id="edit_nom" name="nom_Enseignant" required class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 pl-1">Adresse Email</label>
                    <input type="email" id="edit_email" name="email" required class="w-full border-gray-100 bg-gray-50 p-4 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">Annuler</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleUes(id) {
            const row = document.getElementById(`ues-${id}`);
            const chevron = document.getElementById(`chevron-${id}`);
            const allRows = document.querySelectorAll('[id^="ues-"]');
            const allChevrons = document.querySelectorAll('[id^="chevron-"]');
            const mainRow = row.previousElementSibling;

            const isHidden = row.classList.contains('hidden');

            // Optionnel : fermer les autres pour un vrai comportement d'accordéon
            // allRows.forEach(r => r.classList.add('hidden'));
            // allChevrons.forEach(c => c.style.transform = 'rotate(0deg)');
            // document.querySelectorAll('#enseignantTable tr').forEach(r => r.classList.remove('bg-indigo-50/50'));

            if (isHidden) {
                row.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(90deg)';
                if (mainRow) mainRow.classList.add('bg-indigo-50/50');
            } else {
                row.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
                if (mainRow) mainRow.classList.remove('bg-indigo-50/50');
            }
        }

        function editEnseignant(enseignant) {
            document.getElementById('edit_nom').value = enseignant.nom_Enseignant;
            document.getElementById('edit_email').value = enseignant.email;
            document.getElementById('editForm').action = "/departement/enseignants/" + enseignant.id_Enseignant;
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#enseignantTable tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
