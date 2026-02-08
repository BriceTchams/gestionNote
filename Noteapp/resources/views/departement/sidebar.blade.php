<aside class="w-64 bg-[#0f172a] h-screen fixed text-gray-300 p-4 flex flex-col">
            <div class="flex items-center gap-2 px-2 mb-10 text-white">
                <div class="bg-indigo-600 p-1.5 rounded-lg"><i class="fas fa-graduation-cap"></i></div>
                <span class="text-xl font-bold tracking-tight text-white">UniNotes</span>
            </div>
            <nav class="flex-1 space-y-1">
                <a href="{{ route('departement.dashboard') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-th-large w-5"></i> Tableau de bord
                </a>
                <a href="{{ route('departement.enseignants') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-user-tie w-5"></i> Enseignants
                </a>
                <a href="{{ route('departement.filieres') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-book w-5"></i> Filières & Classes
                </a>
                <a href="{{ route('departement.etudiants') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-user-graduate w-5"></i> Étudiants
                </a>
                    <a href="{{ route('departement.ues') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-layer-group w-5"></i> Ue et groupe
                </a>
                <a href="{{ route('departement.pv') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-file-signature w-5"></i> Publication PV
                </a>

             </nav>
             <form method="POST" action="{{ route('logout') }}" class="mt-4">
                 @csrf
                 <button type="submit" class="w-full flex items-center gap-3 p-3 bg-gray-500 text-white rounded-xl justify-center">
                     <i class="fas fa-right-from-bracket w-5"></i> Se déconnecter
                 </button>
             </form>
        </aside>

