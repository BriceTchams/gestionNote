<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Mes Revendications</title>
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
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex justify-between items-center rounded-r-xl shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-900/50 hover:text-green-900"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        @endif

        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Mes Revendications</h1>
                <p class="text-gray-400 text-sm">Gérez vos réclamations concernant vos notes.</p>
            </div>
            <button onclick="document.getElementById('modalRevendication').classList.remove('hidden')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                <i data-lucide="plus-circle" class="w-5 h-5"></i> Nouvelle revendication
            </button>
        </header>

        <div class="grid grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="bg-orange-50 text-orange-500 p-4 rounded-2xl"><i data-lucide="clock" class="w-6 h-6"></i></div>
                <div>
                    <p class="text-3xl font-black text-gray-800">{{ $stats['en_attente'] }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">En attente</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="bg-green-50 text-green-500 p-4 rounded-2xl"><i data-lucide="check-circle" class="w-6 h-6"></i></div>
                <div>
                    <p class="text-3xl font-black text-gray-800">{{ $stats['approuvees'] }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Approuvées</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="bg-red-50 text-red-500 p-4 rounded-2xl"><i data-lucide="x-circle" class="w-6 h-6"></i></div>
                <div>
                    <p class="text-3xl font-black text-gray-800">{{ $stats['rejetees'] }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rejetées</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-10">
            <h3 class="font-black text-xl text-gray-800 mb-8">Historique des revendications</h3>

            <div class="space-y-6">
                @forelse($revendications as $rev)
                <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-black text-lg text-gray-800">{{ $rev->evaluation->ue->libelle }} ({{ $rev->evaluation->type_Evaluation }})</h4>
                            @php
                                $note = \App\Models\Note::where('id_Evaluation', $rev->id_Evaluation)
                                    ->where('id_Etudiant', $rev->id_Etudiant)
                                    ->first();
                            @endphp
                            <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Note contestée: {{ $note ? number_format($note->valeur, 2) . '/20' : 'N/A' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Enseignant: {{ $rev->evaluation->ue->enseignant->nom_Enseignant ?? 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                            {{ $rev->statut == 'en attente' ? 'bg-orange-100 text-orange-600' : ($rev->statut == 'traitée' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                            {{ $rev->statut }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed italic">"{{ $rev->message }}"</p>

                    @if($rev->reponse_enseignant)
                    <div class="mt-4 p-4 bg-white rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Réponse de l'enseignant :</p>
                        <p class="text-sm text-gray-700">{{ $rev->reponse_enseignant }}</p>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Soumise le {{ $rev->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <i data-lucide="inbox" class="w-12 h-12 text-gray-200 mx-auto mb-4"></i>
                    <p class="text-gray-400">Vous n'avez aucune revendication pour le moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Modal Nouvelle Revendication -->
    <div id="modalRevendication" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-black text-xl text-gray-800">Nouvelle revendication</h3>
                <button onclick="document.getElementById('modalRevendication').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('etudiant.revendications.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Évaluation à contester</label>
                    <div class="relative">
                        <select name="id_Evaluation" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer" required>
                            <option value="">Sélectionner une évaluation</option>
                            @foreach($evaluations as $evaluation)
                                @php
                                    $note = \App\Models\Note::where('id_Evaluation', $evaluation->id_Evaluation)
                                        ->where('id_Etudiant', $etudiant->id_Etudiant)
                                        ->first();
                                @endphp
                                <option value="{{ $evaluation->id_Evaluation }}">
                                    {{ $evaluation->ue->libelle }} - {{ $evaluation->type_Evaluation }} ({{ $note ? number_format($note->valeur, 2) . '/20' : 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-4 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Message / Justification</label>
                    <textarea name="message" rows="4" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-gray-50 text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 focus:bg-white transition" placeholder="Expliquez pourquoi vous contestez cette note..." required></textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white p-4 rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition">
                    Envoyer la revendication
                </button>
            </form>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
