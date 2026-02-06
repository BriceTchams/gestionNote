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
            <a href="{{ route('enseignant.dashboard') }}" class="flex items-center gap-3 hover:bg-slate-800 p-3 rounded-lg text-gray-400 transition"><i data-lucide="home"></i> Tableau de bord</a>
            
            <a href="{{ route('enseignant.saisie') }}" class="flex items-center gap-3 bg-blue-600 p-3 rounded-lg"><i data-lucide="file-edit"></i> Saisie des Notes</a>
                       <a href="{{ route('enseignant.evaluation') }}" class="flex items-center gap-3 bg-blue-600 p-3 rounded-lg shadow-lg shadow-blue-500/20"><i data-lucide="clipboard-list"></i> Évaluations</a>

            <a href="{{ route('enseignant.revendications') }}" class="flex items-center gap-3 hover:bg-slate-800 p-3 rounded-lg text-gray-400 transition"><i data-lucide="message-square"></i> Revendications</a>
        </nav>
        <div class="p-4 bg-[#2d2f45] m-4 rounded-xl"><p class="font-bold">Michel Lefebvre</p></div>
    </aside>

    <main class="flex-1 ml-64 p-8">
        <header class="mb-8"><h1 class="text-2xl font-black text-gray-800 tracking-tight">Saisie des Notes</h1></header>

       <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-1">Critères de sélection</h3>
            <p class="text-gray-400 text-sm mb-8">Sélectionnez la filière, classe, matière et type d'évaluation</p>
            
            <div class="grid grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Filière</label>
                    <div class="relative">
                        <select class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer">
                            <option>Génie Logiciel</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Classe</label>
                    <div class="relative">
                        <select class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer">
                            <option>GL-L3-B</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Matière</label>
                    <div class="relative">
                        <select class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer">
                            <option>GL301 - Génie Logiciel</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">id d'évaluation</label>
                    <div class="relative">
                        <select class="w-full border border-gray-200 p-3 rounded-xl bg-white text-gray-600 outline-none focus:ring-2 ring-indigo-500 appearance-none cursor-pointer">
                            <option>1</option>
                                                        <option>3</option>

                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('enseignant.notes.store') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                <div>
                    <h3 class="font-black text-lg text-gray-800">Saisie des notes</h3>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">GL-L3-B | Génie Logiciel</p>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl flex items-center gap-2 hover:bg-indigo-700 transition font-bold text-sm shadow-lg shadow-indigo-100">
                    <i data-lucide="save" class="w-4 h-4"></i> Enregistrer
                </button>
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
                            <input type="number" name="notes[{{ $etudiant->id_Etudiant }}]" step="0.01" min="0" max="20" class="w-24 border border-gray-200 p-2 rounded-lg text-center outline-none focus:ring-2 ring-indigo-500" placeholder="0-20">
                            <input type="hidden" name="etudiants[{{ $etudiant->id_Etudiant }}]" value="{{ $etudiant->id_Etudiant }}">
                        </td>
                        <td class="px-8 py-5"><span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Inscrit</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>