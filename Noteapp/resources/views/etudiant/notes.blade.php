<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Mes Notes</title>
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
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Mes Notes</h1>
                <p class="text-gray-400 text-sm">Consultez vos résultats académiques par semestre.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-xs font-bold text-white">
                    {{ strtoupper(substr($etudiant->nom_Complet, 0, 1)) }}{{ ($spacePos = strpos($etudiant->nom_Complet, ' ')) ? strtoupper(substr($etudiant->nom_Complet, $spacePos + 1, 1)) : '' }}
                </div>
            </div>
        </header>


        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                <h3 class="font-black text-lg text-gray-800">PV de délibération</h3>
                <span class="text-xs bg-green-50 text-green-600 px-4 py-2 rounded-xl font-black uppercase tracking-wider">Documents officiels</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Année Académique</th>
                            <th class="px-8 py-5 text-center">Semestre</th>
                            <th class="px-8 py-5 text-center">Date de publication</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pvs as $pv)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-8 py-6">
                                <p class="font-bold text-gray-800">{{ $pv->semestre->anneeAcademique->libelle_Annee ?? 'N/A' }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Semestre {{ $pv->semestre->numero }}</span>
                            </td>
                            <td class="px-8 py-6 text-center text-gray-500 font-medium">
                                {{ \Carbon\Carbon::parse($pv->date_Generation)->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('etudiant.pv.download', ['pv_id' => $pv->id_PV]) }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                    <i data-lucide="download" class="w-4 h-4"></i> Télécharger PV
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-500 italic">
                                Aucun PV n'a été publié pour votre classe.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                <h3 class="font-black text-lg text-gray-800">Notes disponibles</h3>
                <span class="text-xs bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl font-black uppercase tracking-wider">{{ $etudiant->classe->lib_Classe ?? '' }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-5">UE</th>
                            <th class="px-8 py-5 text-center">Évaluation</th>
                            <th class="px-8 py-5 text-center">Date</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($evaluations as $evaluation)
                        @php $note = $notes->get($evaluation->id_Evaluation); @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-8 py-6">
                                <p class="font-bold text-gray-800">{{ $evaluation->ue->libelle }}</p>
                                <p class="text-[10px] text-gray-400 font-black uppercase">{{ $evaluation->ue->code }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">{{ $evaluation->type_Evaluation }}</span>
                            </td>
                            <td class="px-8 py-6 text-center text-gray-500 font-medium">
                                {{ ($note && $note->date_Saisie) ? \Carbon\Carbon::parse($note->date_Saisie)->format('d/m/Y') : ($evaluation->date_Evaluation ? \Carbon\Carbon::parse($evaluation->date_Evaluation)->format('d/m/Y') : '-') }}
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('etudiant.notes.download-pdf', [
                                    'filiere_id' => $etudiant->classe->id_Filiere,
                                    'classe_id' => $etudiant->id_Classe,
                                    'ue_id' => $evaluation->id_UE,
                                    'evaluation_id' => $evaluation->id_Evaluation
                                ]) }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                                    <i data-lucide="file-down" class="w-4 h-4"></i> Télécharger
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="bg-gray-50 rounded-2xl p-8 border border-dashed border-gray-200">
                                    <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                                    <p class="text-gray-500 font-medium">Aucune note disponible pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
