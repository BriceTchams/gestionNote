<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Tableau de bord Étudiant</title>
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
            <a href="{{ route('etudiant.dashboard') }}" class="flex items-center gap-3 {{ request()->routeIs('etudiant.dashboard') ? 'bg-blue-600' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="home"></i> Tableau de bord</a>
            <a href="{{ route('etudiant.notes') }}" class="flex items-center gap-3 {{ request()->routeIs('etudiant.notes') ? 'bg-blue-600' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="file-text"></i> Mes Notes</a>
            <a href="{{ route('etudiant.revendications') }}" class="flex items-center gap-3 {{ request()->routeIs('etudiant.revendications') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-gray-400' }} p-3 rounded-lg transition"><i data-lucide="message-square"></i> Revendications</a>
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
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tableau de bord</h1>
            <div class="flex items-center gap-4">
                <i data-lucide="bell" class="text-gray-500 relative cursor-pointer"><span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span></i>
                <div class="flex items-center gap-2 bg-white p-2 px-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-[10px] font-bold text-white">
                        {{ strtoupper(substr($etudiant->nom_Complet, 0, 1)) }}{{ ($spacePos = strpos($etudiant->nom_Complet, ' ')) ? strtoupper(substr($etudiant->nom_Complet, $spacePos + 1, 1)) : '' }}
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ $etudiant->nom_Complet }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
        </header>

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-10 text-white flex justify-between items-center mb-8 shadow-xl shadow-indigo-100">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Bonjour, {{ explode(' ', $etudiant->nom_Complet)[0] }} ! 👋</h2>
                <p class="opacity-90 font-medium">Classe : {{ $etudiant->classe->lib_Classe ?? 'Non définie' }} | Matricule : {{ $etudiant->matricule_Et }}</p>
            </div>
            <a href="{{ route('etudiant.notes') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-2xl flex items-center gap-2 transition backdrop-blur-md font-semibold">
                Voir mes notes <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start max-w-xs">
                <div><p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Revendications</p><p class="text-2xl font-black">{{ $nbRevendications }}</p></div>
                <div class="bg-orange-100 p-2 rounded-xl text-orange-500"><i data-lucide="message-circle" class="w-5 h-5"></i></div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black text-xl text-gray-800">Notes Récentes</h3>
                    <a href="{{ route('etudiant.notes') }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">Voir tout</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Matière</th>
                                <th class="px-6 py-4 text-center">Évaluation</th>
                                <th class="px-6 py-4 text-center">Note</th>
                                <th class="px-6 py-4 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($notesRecentes as $note)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $note->evaluation->ue->libelle ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center text-gray-600">{{ $note->evaluation->type_Evaluation ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center font-black text-indigo-600">{{ number_format($note->valeur, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($note->valeur >= 10)
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Validé</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Non Validé</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Aucune note récente disponible.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
