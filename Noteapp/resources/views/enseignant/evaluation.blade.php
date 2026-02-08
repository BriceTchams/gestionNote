<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Gestion des Évaluations</title>
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
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex justify-between items-center rounded-r-xl shadow-sm animate-bounce">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-900/50 hover:text-green-900"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Gestion des Évaluations</h1>
                <p class="text-gray-400 text-sm">Créez et gérez les types d'examens pour vos matières.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right mr-4 border-r pr-4 border-gray-200">
                    <p class="text-[10px] font-black text-indigo-500 uppercase">Semestre en cours</p>
                    <p class="font-bold text-gray-700 text-sm">
                        @if($semestres->isNotEmpty())
                            Semestre {{ $semestres->first()->numero }} (Dernier)
                        @else
                            Aucun semestre défini
                        @endif
                    </p>
                </div>
                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-xs font-bold text-white">
                    @php $name = Auth::guard('enseignant')->user()->nom_Enseignant; @endphp
                    {{ strtoupper(substr($name, 0, 1)) }}{{ ($spacePos = strpos($name, ' ')) ? strtoupper(substr($name, $spacePos + 1, 1)) : '' }}
                </div>
            </div>
        </header>

        <div class="grid grid-cols-12 gap-8">

            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 sticky top-8">
                    <h3 class="font-black text-gray-800 mb-6 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="text-indigo-600 w-5 h-5"></i>
                        Nouvelle Évaluation
                    </h3>

                    <form action="{{ route('enseignant.evaluation.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Libellé</label>
                            <input type="text" name="type_Evaluation" placeholder="ex: Examen Final" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 focus:bg-white transition" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Type d'évaluation</label>
                            <div class="relative">
                                <select name="type_Evaluation" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="CC">Contrôle Continu (CC)</option>
                                    <option value="Examen">Examen</option>
                                    <option value="TP">Travaux Pratiques (TP)</option>
                                    <option value="Rattrapage">Rattrapage</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-4 top-4 w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Matière concernée</label>
                            <div class="relative">
                                <select name="id_UE" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" required>
                                    <option value="">Sélectionner une matière</option>
                                    @foreach($ues as $ue)
                                        <option value="{{ $ue->id_UE }}">{{ $ue->libelle }} ({{ $ue->code }})</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-4 top-4 w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Semestre</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($semestres as $semestre)
                                    <label class="relative flex items-center justify-center p-4 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 {{ $loop->first ? 'border-indigo-600 bg-indigo-50' : 'border-gray-50 bg-gray-50' }}" id="label_semestre_{{ $semestre->id_Semestre }}">
                                        <input type="radio" name="id_Semestre" value="{{ $semestre->id_Semestre }}" class="hidden" {{ $loop->first ? 'checked' : '' }} onchange="updateSemesterSelection(this)">
                                        <span class="font-bold text-gray-700">Semestre {{ $semestre->numero }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <script>
                            function updateSemesterSelection(radio) {
                                // Réinitialiser tous les labels
                                document.querySelectorAll('[id^="label_semestre_"]').forEach(el => {
                                    el.classList.remove('border-indigo-600', 'bg-indigo-50');
                                    el.classList.add('border-gray-50', 'bg-gray-50');
                                });
                                // Mettre en évidence le sélectionné
                                const label = document.getElementById('label_semestre_' + radio.value);
                                label.classList.remove('border-gray-50', 'bg-gray-50');
                                label.classList.add('border-indigo-600', 'bg-indigo-50');
                            }
                        </script>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Date de l'évaluation</label>
                            <input type="date" name="date_Evaluation" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 focus:bg-white transition" required>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white p-4 rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition flex justify-center items-center gap-2 mt-4">
                            <i data-lucide="check" class="w-5 h-5"></i> Créer l'évaluation
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-white">
                        <h3 class="font-black text-lg text-gray-800">Liste des évaluations</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                                <tr>
                                    <th class="px-8 py-5">Évaluation</th>
                                    <th class="px-8 py-5">Matière</th>
                                    <th class="px-8 py-5 text-center">Date</th>
                                    <th class="px-8 py-5 text-center">Poids</th>
                                    <th class="px-8 py-5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @foreach($evaluations as $evaluation)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xs">{{ substr($evaluation->type_Evaluation, 0, 2) }}</div>
                                            <span class="font-bold text-gray-800">{{ $evaluation->type_Evaluation }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-gray-500">{{ $evaluation->ue->libelle ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-indigo-400 uppercase font-black">Semestre {{ $evaluation->semestre->numero ?? '' }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-center font-bold text-gray-600">
                                        {{ \Carbon\Carbon::parse($evaluation->date_Evaluation)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-8 py-6 text-center font-black text-indigo-600 bg-indigo-50/30">
                                        @if($evaluation->type_Evaluation == 'CC')
                                            30%
                                        @elseif($evaluation->type_Evaluation == 'Examen')
                                            70%
                                        @elseif($evaluation->type_Evaluation == 'TP')
                                            20%
                                        @elseif($evaluation->type_Evaluation == 'Rattrapage')
                                            70%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button class="p-2 hover:bg-indigo-50 text-indigo-600 rounded-lg transition"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                            <button class="p-2 hover:bg-red-50 text-red-500 rounded-lg transition"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
