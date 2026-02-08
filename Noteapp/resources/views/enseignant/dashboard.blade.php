<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Tableau de bord</title>
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
                @if($nbRevendications > 0)
                    <span class="absolute right-2 top-3 bg-red-500 text-xs px-2 py-0.5 rounded-full font-bold text-white">{{ $nbRevendications }}</span>
                @endif
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
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tableau de bord</h1>
            <div class="flex items-center gap-4">
                <i data-lucide="bell" class="text-gray-500 relative cursor-pointer"><span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span></i>
                <div class="flex items-center gap-2 bg-white p-2 px-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-[10px] font-bold text-white">
                        {{ strtoupper(substr($enseignant->nom_Enseignant, 0, 1)) }}{{ ($spacePos = strpos($enseignant->nom_Enseignant, ' ')) ? strtoupper(substr($enseignant->nom_Enseignant, $spacePos + 1, 1)) : '' }}
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ $enseignant->nom_Enseignant }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
        </header>

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-10 text-white flex justify-between items-center mb-8 shadow-xl shadow-indigo-100">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Bonjour, {{ explode(' ', $enseignant->nom_Enseignant)[0] }} ! 👋</h2>
                <p class="opacity-90 font-medium">Département : {{ $enseignant->departement->nom_Departement ?? 'Non défini' }}</p>
            </div>
            <a href="{{ route('enseignant.saisie') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-2xl flex items-center gap-2 transition backdrop-blur-md font-semibold">
                Saisir des notes <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-5 gap-4 mb-10">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Matières</p><p class="text-2xl font-black">{{ $nbMatieres }}</p></div>
                <div class="bg-blue-100 p-2 rounded-xl text-blue-600"><i data-lucide="book-open" class="w-5 h-5"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Classes</p><p class="text-2xl font-black">{{ $nbClasses }}</p></div>
                <div class="bg-purple-100 p-2 rounded-xl text-purple-600"><i data-lucide="users" class="w-5 h-5"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Évaluations</p><p class="text-2xl font-black">{{ $nbEvaluations }}</p></div>
                <div class="bg-indigo-100 p-2 rounded-xl text-indigo-600"><i data-lucide="clipboard-list" class="w-5 h-5"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Étudiants</p><p class="text-2xl font-black">{{ $nbEtudiants }}</p></div>
                <div class="bg-gray-100 p-2 rounded-xl text-gray-600"><i data-lucide="user-plus" class="w-5 h-5"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-[10px] font-bold uppercase mb-1">Revendications</p><p class="text-2xl font-black">{{ $nbRevendications }}</p></div>
                <div class="bg-orange-100 p-2 rounded-xl text-orange-500"><i data-lucide="message-circle" class="w-5 h-5"></i></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black text-xl text-gray-800">Mes Matières</h3>
                    <a href="{{ route('enseignant.evaluation') }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">Gérer</a>
                </div>
                <div class="space-y-4">
                    @forelse($ues as $ue)
                    <div class="p-5 bg-gray-50 rounded-2xl flex justify-between items-center border border-transparent hover:border-indigo-100 transition">
                        <div>
                            <p class="font-bold text-gray-800">{{ $ue->libelle }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $ue->code }} | {{ $ue->groupe_ue->filiere->nom_Filiere ?? 'Filière' }}</p>
                        </div>
                        <span class="text-xs bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 font-black text-gray-600 uppercase">Crédits: {{ $ue->credits }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Aucune matière assignée.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black text-xl text-gray-800 flex items-center gap-2">Revendications <span class="bg-red-500 text-white text-[10px] px-2 py-1 rounded-full font-bold">{{ $nbRevendications }}</span></h3>
                    <a href="{{ route('enseignant.revendications') }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">Voir tout</a>
                </div>

                {{-- Affichage des revendications --}}
                <div class="space-y-4">
                    @forelse($dernieresRevendications as $rev)
                        <div class="bg-orange-50/50 p-6 rounded-2xl border border-orange-100/50">
                            <div class="flex justify-between mb-3">
                                <p class="font-black text-gray-800">{{ $rev->etudiant->nom_Complet }}</p>
                                <span class="text-[10px] font-black uppercase text-orange-500 flex items-center gap-1 bg-white px-2 py-1 rounded-lg shadow-sm">{{ $rev->statut }}</span>
                            </div>
                            <p class="text-[10px] text-indigo-500 mb-3 font-black uppercase tracking-widest">{{ $rev->evaluation->ue->libelle }} ({{ $rev->evaluation->type_Evaluation }})</p>
                            <p class="text-sm text-gray-500 italic leading-relaxed">"{{ Str::limit($rev->message, 100) }}"</p>
                        </div>
                    @empty
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 text-center">
                            <p class="text-gray-500 text-sm">Aucune revendication en attente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
