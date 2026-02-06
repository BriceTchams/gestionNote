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
            <a href="{{ route('enseignant.dashboard') }}" class="flex items-center gap-3 bg-blue-600 p-3 rounded-lg"><i data-lucide="home"></i> Tableau de bord</a>
            <a href="{{ route('enseignant.saisie') }}" class="flex items-center gap-3 hover:bg-slate-800 p-3 rounded-lg text-gray-400 transition"><i data-lucide="file-edit"></i> Saisie des Notes</a>
                                  <a href="{{ route('enseignant.evaluation') }}" class="flex items-center gap-3 bg-blue-600 p-3 rounded-lg shadow-lg shadow-blue-500/20"><i data-lucide="clipboard-list"></i> Évaluations</a>

            <div class="relative">
                <a href="{{ route('enseignant.revendications') }}" class="flex items-center gap-3 hover:bg-slate-800 p-3 rounded-lg text-gray-400 transition"><i data-lucide="message-square"></i> Revendications</a>
                <span class="absolute right-2 top-3 bg-red-500 text-xs px-2 py-0.5 rounded-full font-bold">3</span>
            </div>
        </nav>

        <div class="p-4 bg-[#2d2f45] m-4 rounded-xl">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Enseignant</p>
            <p class="font-bold">Michel Lefebvre</p>
            <p class="text-xs text-gray-400 italic font-light">Informatique</p>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tableau de bord</h1>
            <div class="flex items-center gap-4">
                <i data-lucide="bell" class="text-gray-500 relative cursor-pointer"><span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span></i>
                <div class="flex items-center gap-2 bg-white p-2 px-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-[10px] font-bold">ML</div>
                    <span class="text-sm font-semibold text-gray-700">Michel Lefebvre</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
        </header>

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-10 text-white flex justify-between items-center mb-8 shadow-xl shadow-indigo-100">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Bonjour, Michel ! 👋</h2>
                <p class="opacity-90 font-medium">Département : Informatique</p>
            </div>
            <a href="{{ route('enseignant.saisie') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-2xl flex items-center gap-2 transition backdrop-blur-md font-semibold">
                Saisir des notes <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-xs font-bold uppercase mb-1">Matières</p><p class="text-3xl font-black">3</p></div>
                <div class="bg-blue-100 p-3 rounded-2xl text-blue-600"><i data-lucide="book-open"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-xs font-bold uppercase mb-1">Classes</p><p class="text-3xl font-black">5</p></div>
                <div class="bg-purple-100 p-3 rounded-2xl text-purple-600"><i data-lucide="users"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-xs font-bold uppercase mb-1">Étudiants</p><p class="text-3xl font-black">8</p></div>
                <div class="bg-gray-100 p-3 rounded-2xl text-gray-600"><i data-lucide="user-plus"></i></div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div><p class="text-gray-400 text-xs font-bold uppercase mb-1">Revendications</p><p class="text-3xl font-black">1</p></div>
                <div class="bg-orange-100 p-3 rounded-2xl text-orange-500"><i data-lucide="message-circle"></i></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black text-xl text-gray-800">Mes Matières</h3>
                    <button class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">Gérer</button>
                </div>
                <div class="space-y-4">
                    <div class="p-5 bg-gray-50 rounded-2xl flex justify-between items-center border border-transparent hover:border-indigo-100 transition">
                        <div><p class="font-bold text-gray-800">Programmation Avancée</p><p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">PA301 | Semestre 1</p></div>
                        <span class="text-xs bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 font-black text-gray-600 uppercase">Coef. 3</span>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-2xl flex justify-between items-center border border-transparent hover:border-indigo-100 transition">
                        <div><p class="font-bold text-gray-800">Génie Logiciel</p><p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">GL301 | Semestre 1</p></div>
                        <span class="text-xs bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 font-black text-gray-600 uppercase">Coef. 3</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black text-xl text-gray-800 flex items-center gap-2">Revendications <span class="bg-red-500 text-white text-[10px] px-2 py-1 rounded-full font-bold">1</span></h3>
                    <a href="{{ route('enseignant.revendications') }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">Voir tout</a>
                </div>
                <div class="bg-orange-50/50 p-6 rounded-2xl border border-orange-100/50">
                    <div class="flex justify-between mb-3">
                        <p class="font-black text-gray-800">Jean Dupont</p>
                        <span class="text-[10px] font-black uppercase text-orange-500 flex items-center gap-1 bg-white px-2 py-1 rounded-lg shadow-sm">En attente</span>
                    </div>
                    <p class="text-[10px] text-indigo-500 mb-3 font-black uppercase tracking-widest">Génie Logiciel</p>
                    <p class="text-sm text-gray-500 italic leading-relaxed">"Je pense qu'il y a une erreur dans le calcul de ma note d'examen..."</p>
                </div>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>