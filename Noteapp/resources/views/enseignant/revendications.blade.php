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
            <a href="{{ route('enseignant.dashboard') }}" class="flex items-center gap-3 hover:bg-slate-800 p-3 rounded-lg text-gray-400 transition"><i data-lucide="home"></i> Tableau de bord</a>
            <a href="{{ route('enseignant.saisie') }}" class="flex items-center gap-3 hover:bg-slate-800 p-3 rounded-lg text-gray-400 transition"><i data-lucide="file-edit"></i> Saisie des Notes</a>
                                  <a href="{{ route('enseignant.evaluation') }}" class="flex items-center gap-3 bg-blue-600 p-3 rounded-lg shadow-lg shadow-blue-500/20"><i data-lucide="clipboard-list"></i> Évaluations</a>

            <a href="{{ route('enseignant.revendications') }}" class="flex items-center gap-3 bg-blue-600 p-3 rounded-lg transition shadow-lg shadow-blue-500/20"><i data-lucide="message-square"></i> Revendications</a>
        </nav>
        <div class="p-4 bg-[#2d2f45] m-4 rounded-xl"><p class="font-bold">Michel Lefebvre</p></div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <header class="mb-10"><h1 class="text-2xl font-black text-gray-800 tracking-tight">Gestion des Revendications</h1></header>

        <div class="grid grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-orange-50 text-orange-500 p-4 rounded-2xl"><i data-lucide="clock" class="w-6 h-6"></i></div>
                <div><p class="text-3xl font-black text-gray-800">1</p><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">En attente</p></div>
            </div>
            </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10">
            <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-6">
                <button class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                    En attente <span class="bg-red-500 text-[10px] px-2 py-0.5 rounded-full">1</span>
                </button>
                <button class="text-gray-400 px-6 py-2.5 hover:bg-gray-50 rounded-xl transition font-bold text-sm">Traitées</button>
            </div>

            <div class="border border-gray-100 rounded-[2rem] p-8 hover:border-indigo-200 transition bg-white shadow-sm">
                <div class="flex justify-between items-start mb-8">
                    <div class="flex gap-5">
                        <div class="w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center border border-gray-100">
                            <i data-lucide="user" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <p class="font-black text-xl text-gray-800">Jean Dupont</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Matricule: ETU2024001 • Classe: GL-L3-A</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-400 flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-full"><i data-lucide="calendar" class="w-3 h-3"></i> 15 Janvier 2025</span>
                </div>

                <div class="bg-gray-50/50 rounded-3xl p-8 mb-8 border border-gray-50">
                    <div class="flex gap-2 items-center mb-5">
                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase shadow-md shadow-indigo-100">GL301</span>
                        <span class="text-gray-400 font-bold text-xs uppercase tracking-widest">Génie Logiciel</span>
                    </div>
                    <p class="font-black text-gray-800 text-lg mb-4">Note actuelle : <span class="text-indigo-600 underline decoration-indigo-200 decoration-4 underline-offset-4">11.8/20</span></p>
                    <p class="text-gray-500 italic leading-relaxed text-sm bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        "Je pense qu'il y a une erreur dans le calcul de ma note d'examen. J'ai vérifié mes réponses et je devrais avoir au moins 13."
                    </p>
                </div>

                <div class="flex justify-end gap-4">
                    <button class="border-2 border-green-500/20 text-green-600 hover:bg-green-500 hover:text-white px-8 py-3 rounded-2xl font-black text-sm flex items-center gap-2 transition duration-300">
                        <i data-lucide="check-circle" class="w-5 h-5"></i> Approuver
                    </button>
                    <button class="border-2 border-red-500/20 text-red-600 hover:bg-red-500 hover:text-white px-8 py-3 rounded-2xl font-black text-sm flex items-center gap-2 transition duration-300">
                        <i data-lucide="x-circle" class="w-5 h-5"></i> Rejeter
                    </button>
                </div>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>