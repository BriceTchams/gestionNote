<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Saisie des Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 flex">
    <aside class="w-64 bg-[#1a1c2e] min-h-screen text-white flex flex-col fixed">
        <div class="p-6 flex items-center gap-3">
            <div class="bg-indigo-600 p-2 rounded-lg"><i data-lucide="graduation-cap"></i></div>
            <span class="text-xl font-bold">UniNotes</span>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="{{ route('enseignant.dashboard') }}" class="flex items-center gap-3 {{ request()->routeIs('enseignant.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="home"></i> Tableau de bord</a>
            <a href="{{ route('enseignant.saisie') }}" class="flex items-center gap-3 {{ request()->routeIs('enseignant.saisie') ? 'bg-blue-600' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="file-edit"></i> Saisie des Notes</a>
            <a href="{{ route('enseignant.evaluation') }}" class="flex items-center gap-3 {{ request()->routeIs('enseignant.evaluation') ? 'bg-blue-600' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="clipboard-list"></i> Évaluations</a>

            <div class="relative">
                <a href="{{ route('enseignant.revendications') }}" class="flex items-center gap-3 {{ request()->routeIs('enseignant.revendications') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="message-square"></i> Revendications</a>
            </div>
        </nav>
        <div class="p-4 m-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full text-red-400 hover:text-red-300 hover:bg-red-500/10 p-3 rounded-lg transition">
                    <i data-lucide="log-out"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex justify-between items-center rounded-r-xl shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-900/50 hover:text-green-900"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        <header class="mb-8"><h1 class="text-2xl font-black text-gray-800 tracking-tight">Saisie des Notes</h1></header>

       <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-1">Critères de sélection</h3>
            <p class="text-gray-400 text-sm mb-8">Sélectionnez la filière, classe, matière et type d'évaluation</p>

            <form method="GET" action="{{ route('enseignant.saisie') }}" id="filterForm">
                <div class="grid grid-cols-4 gap-4 items-end">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Filière</label>
                        <div class="relative">
                            <select name="filiere_id" id="filiere_id" class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" onchange="this.form.submit()">
                                <option value="">-- Sélectionner --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id_Filiere }}" {{ $selectedFiliere == $filiere->id_Filiere ? 'selected' : '' }}>
                                        {{ $filiere->nom_Filiere }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Classe</label>
                        <div class="relative">
                            <select name="classe_id" id="classe_id" class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" onchange="this.form.submit()" {{ $classes->isEmpty() ? 'disabled' : '' }}>
                                <option value="">-- Sélectionner --</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id_Classe }}" {{ $selectedClasse == $classe->id_Classe ? 'selected' : '' }}>
                                        {{ $classe->lib_Classe }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Matière (UE)</label>
                        <div class="relative">
                            <select name="ue_id" id="ue_id" class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" onchange="this.form.submit()">
                                <option value="">-- Sélectionner --</option>
                                @foreach($ues as $ue)
                                    <option value="{{ $ue->id_UE }}" {{ request('ue_id') == $ue->id_UE ? 'selected' : '' }}>
                                        {{ $ue->code }} - {{ $ue->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Évaluation</label>
                        <div class="relative">
                            <select name="evaluation_id" id="evaluation_id" class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" {{ $evaluations->isEmpty() ? 'disabled' : '' }}>
                                <option value="">-- Sélectionner --</option>
                                @foreach($evaluations as $evaluation)
                                    <option value="{{ $evaluation->id_Evaluation }}" {{ $selectedEvaluation == $evaluation->id_Evaluation ? 'selected' : '' }}>
                                        {{ $evaluation->type_Evaluation }} - {{ $evaluation->date_Evaluation }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="col-span-4 flex justify-center mt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition font-bold shadow-lg shadow-indigo-100">
                            <i data-lucide="users" class="w-5 h-5"></i> Afficher les étudiants
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($selectedEvaluation && $etudiants->isNotEmpty())
        <form action="{{ route('enseignant.notes.store') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            <input type="hidden" name="evaluation_id" value="{{ $selectedEvaluation }}">

            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                <div>
                    <h3 class="font-black text-lg text-gray-800">Saisie des notes</h3>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">
                        {{ $classes->where('id_Classe', $selectedClasse)->first()->lib_Classe ?? '' }} |
                        {{ $ues->where('id_UE', request('ue_id'))->first()->libelle ?? '' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('enseignant.saisie.download-pdf', [
                        'filiere_id' => $selectedFiliere,
                        'classe_id' => $selectedClasse,
                        'ue_id' => request('ue_id'),
                        'evaluation_id' => $selectedEvaluation
                    ]) }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl flex items-center gap-2 hover:bg-gray-200 transition font-bold text-sm">
                        <i data-lucide="download" class="w-4 h-4"></i> Télécharger PDF
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition font-bold text-sm shadow-lg shadow-indigo-100">
                        <i data-lucide="save" class="w-4 h-4"></i> Enregistrer les notes
                    </button>
                </div>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-5">#</th>
                        <th class="px-8 py-5">Matricule</th>
                        <th class="px-8 py-5 text-center">Nom & Prénom</th>
                        <th class="px-8 py-5 w-40 text-center">Note /20</th>
                        <th class="px-8 py-5">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($etudiants as $index => $etudiant)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-8 py-5 text-gray-800 font-bold">{{ $index + 1 }}</td>
                        <td class="px-8 py-5 font-mono text-gray-600">{{ $etudiant->matricule_Et }}</td>
                        <td class="px-8 py-5 text-center text-gray-800 font-bold">{{ $etudiant->nom_Complet }}</td>
                        <td class="px-8 py-5 w-40 text-center">
                            <input type="number" name="notes[{{ $etudiant->id_Etudiant }}]" step="0.01" min="0" max="20"
                                class="w-24 border border-gray-200 p-2 rounded-lg text-center outline-none focus:ring-2 ring-indigo-500"
                                placeholder="0-20"
                                value="{{ $existingNotes[$etudiant->id_Etudiant] ?? '' }}">
                        </td>
                        <td class="px-8 py-5"><span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Inscrit</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @elseif($selectedClasse && $etudiants->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-xl">
                <div class="flex items-center gap-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600"></i>
                    <p class="text-yellow-800 font-semibold">Aucun étudiant trouvé dans cette classe.</p>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-xl">
                <div class="flex items-center gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    <p class="text-blue-800 font-semibold">Veuillez sélectionner une filière, une classe, une matière et une évaluation pour commencer la saisie des notes.</p>
                </div>
            </div>
        @endif
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
