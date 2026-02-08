<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Revendications</title>
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

        <header class="mb-10"><h1 class="text-2xl font-black text-gray-800 tracking-tight">Gestion des Revendications</h1></header>

        <div class="grid grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-orange-50 text-orange-500 p-4 rounded-2xl"><i data-lucide="clock" class="w-6 h-6"></i></div>
                <div><p class="text-3xl font-black text-gray-800">{{ $stats['en_attente'] }}</p><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">En attente</p></div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-green-50 text-green-500 p-4 rounded-2xl"><i data-lucide="check-circle" class="w-6 h-6"></i></div>
                <div><p class="text-3xl font-black text-gray-800">{{ $stats['traitees'] }}</p><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Traitées</p></div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
            <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-6">
                <button onclick="filterRev('en attente')" class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                    En attente <span class="bg-red-500 text-[10px] px-2 py-0.5 rounded-full">{{ $stats['en_attente'] }}</span>
                </button>
                <button onclick="filterRev('traitees')" class="text-gray-400 px-6 py-2.5 hover:bg-gray-50 rounded-xl transition font-bold text-sm">Traitées</button>
            </div>

            <div class="space-y-6">
                @forelse($revendications as $rev)
                <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 rev-card" data-status="{{ $rev->statut == 'en attente' ? 'en attente' : 'traitees' }}">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="font-black text-lg text-gray-800">{{ $rev->etudiant->nom_Complet }}</h3>
                            <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest">{{ $rev->evaluation->ue->libelle }} ({{ $rev->evaluation->type_Evaluation }})</p>
                            @php
                                $note = \App\Models\Note::where('id_Evaluation', $rev->id_Evaluation)
                                    ->where('id_Etudiant', $rev->id_Etudiant)
                                    ->first();
                            @endphp
                            <p class="text-xs font-bold text-gray-400 mt-1">Note actuelle: {{ $note ? number_format($note->valeur, 2) . '/20' : 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                            {{ $rev->statut == 'en attente' ? 'bg-orange-100 text-orange-600' : ($rev->statut == 'traitée' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                            {{ $rev->statut }}
                        </span>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-gray-100 mb-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Message de l'étudiant :</p>
                        <p class="text-gray-600 italic">"{{ $rev->message }}"</p>
                    </div>

                    @if($rev->statut == 'en attente')
                    <form action="{{ route('enseignant.revendications.update', $rev->id_Revendication) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Votre réponse</label>
                            <textarea name="reponse_enseignant" rows="3" class="w-full border-2 border-gray-50 p-4 rounded-2xl bg-white text-gray-700 font-bold outline-none focus:ring-2 ring-indigo-500 transition" placeholder="Expliquez votre décision..." required></textarea>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" name="statut" value="traitée" class="flex-1 bg-green-600 text-white p-4 rounded-2xl font-black text-sm shadow-xl shadow-green-100 hover:bg-green-700 transition flex justify-center items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i> Accepter / Rectifier
                            </button>
                            <button type="submit" name="statut" value="rejetée" class="flex-1 bg-red-600 text-white p-4 rounded-2xl font-black text-sm shadow-xl shadow-red-100 hover:bg-red-700 transition flex justify-center items-center gap-2">
                                <i data-lucide="x-circle" class="w-5 h-5"></i> Rejeter
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="p-6 bg-white rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Votre réponse :</p>
                        <p class="text-gray-700">{{ $rev->reponse_enseignant }}</p>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        Reçue le {{ $rev->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
                @empty
                <div class="bg-gray-50 p-12 rounded-[2rem] border border-dashed border-gray-200 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i data-lucide="inbox" class="w-10 h-10 text-gray-200"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-2">Aucune revendication</h3>
                    <p class="text-gray-400">Toutes les demandes ont été traitées ou aucune n'a été soumise pour le moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <script>
        function filterRev(status) {
            document.querySelectorAll('.rev-card').forEach(card => {
                if (status === 'all') {
                    card.classList.remove('hidden');
                } else {
                    if (card.getAttribute('data-status') === status) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                }
            });

            // Update button styles
            event.target.closest('div').querySelectorAll('button').forEach(btn => {
                btn.classList.remove('bg-gray-900', 'text-white');
                btn.classList.add('text-gray-400');
            });
            event.target.classList.remove('text-gray-400');
            event.target.classList.add('bg-gray-900', 'text-white');
        }

        // Initial filter
        window.onload = () => filterRev('en attente');
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>
