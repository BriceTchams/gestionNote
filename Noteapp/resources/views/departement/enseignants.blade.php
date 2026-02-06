<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Enseignants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] font-sans">
    <div class="flex">
       @include('departement.sidebar')

        <main class="ml-64 flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Gestion des Enseignants</h2>
                    <p class="text-sm text-gray-400">Département Informatique</p>
                </div>
                <button onclick="document.getElementById('modalAdd').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-lg shadow-indigo-100">
                    <i class="fas fa-plus"></i> Ajouter un enseignant
                </button>
            </header>

            <div class="bg-white p-4 rounded-2xl mb-8 border flex items-center gap-3">
                <i class="fas fa-search text-gray-400 ml-2"></i>
                <input type="text" placeholder="Rechercher un enseignant..." class="flex-1 outline-none text-sm text-gray-600">
            </div>

            <div class="grid grid-cols-3 gap-6 mb-8 text-center">
                <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-blue-600 text-2xl font-bold">4</p><p class="text-gray-400 text-sm">Enseignants</p></div>
                <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-purple-600 text-2xl font-bold">8</p><p class="text-gray-400 text-sm">Matières attribuées</p></div>
                <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-green-600 text-2xl font-bold">4.5</p><p class="text-gray-400 text-sm">Matières/Enseignant (moy.)</p></div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b"><h3 class="font-bold">Liste des enseignants</h3><p class="text-xs text-gray-400">4 enseignant(s) trouvé(s)</p></div>
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] tracking-widest">
                        <tr>
                            <th class="p-4 pl-8">Enseignant</th>
                            <th class="p-4 text-center">Email</th>
                            <th class="p-4">Matières</th>
                            <th class="p-4 pr-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 pl-8 flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center"><i class="far fa-user"></i></div>
                                <div><p class="font-semibold text-gray-800">Michel Lefebvre</p><p class="text-xs text-gray-400">Informatique</p></div>
                            </td>
                            <td class="p-4 text-center text-gray-500"><i class="far fa-envelope mr-2"></i>michel.lefebvre@univ.edu</td>
                            <td class="p-4 flex gap-1">
                                <span class="bg-purple-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">PA301</span>
                                <span class="bg-indigo-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">GL301</span>
                                <span class="bg-gray-100 text-gray-400 text-[10px] px-2 py-1 rounded font-bold">+1</span>
                            </td>
                            <td class="p-4 pr-8 text-right space-x-2">
                                <button class="text-gray-400 hover:text-indigo-600"><i class="fas fa-pen"></i></button>
                                <button class="text-gray-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 pl-8 flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center"><i class="far fa-user"></i></div>
                                <div><p class="font-semibold text-gray-800">Anne Garcia</p><p class="text-xs text-gray-400">Informatique</p></div>
                            </td>
                            <td class="p-4 text-center text-gray-500"><i class="far fa-envelope mr-2"></i>anne.garcia@univ.edu</td>
                            <td class="p-4 flex gap-1">
                                <span class="bg-purple-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">BD301</span>
                                <span class="bg-indigo-600 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">IA302</span>
                            </td>
                            <td class="p-4 pr-8 text-right space-x-2">
                                <button class="text-gray-400 hover:text-indigo-600"><i class="fas fa-pen"></i></button>
                                <button class="text-gray-400 hover:text-red-500"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="modalAdd" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white w-[500px] rounded-3xl p-8 shadow-2xl">
            <h2 class="text-xl font-bold mb-6">Ajouter un Enseignant</h2>
            <form class="space-y-4">
                <div><label class="block text-sm font-medium mb-1">Nom complet</label><input type="text" class="w-full border p-2.5 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500"></div>
                <div><label class="block text-sm font-medium mb-1">Email</label><input type="email" class="w-full border p-2.5 rounded-xl bg-gray-50 outline-none focus:ring-2 focus:ring-indigo-500"></div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('modalAdd').classList.add('hidden')" class="px-6 py-2.5 bg-gray-100 rounded-xl font-medium">Annuler</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-medium">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>