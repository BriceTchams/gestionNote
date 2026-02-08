<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniNotes - Historique PV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] font-sans">
    <div class="flex">
        @include('departement.sidebar')

        <main class="ml-64 flex-1 p-8">
             <header class="flex justify-between items-center mb-8">
                <h2 class="text-lg font-semibold text-gray-700">Historique des PV</h2>
                <div class="flex items-center gap-5">
                    <i class="far fa-bell text-gray-400"></i>
                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border shadow-sm">
                        <div class="w-8 h-8 bg-indigo-700 rounded-full flex items-center justify-center text-xs text-white">DML</div>
                        <span class="text-sm font-medium">Dr. Martin Leroy</span>
                    </div>
                </div>
            </header>

            <h1 class="text-2xl font-bold text-gray-800">Historique des Procès-Verbaux</h1>
            <p class="text-sm text-gray-400 mb-8">Consultez et téléchargez les PV précédemment générés</p>

            <div class="flex gap-2 mb-8 bg-white p-1 rounded-xl w-fit shadow-sm border">
                <a href="{{ route('departement.pv') }}" class="text-gray-400 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium text-sm transition">Publier un PV</a>
                <a href="{{ route('departement.pv.history') }}" class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-medium text-sm">Historique</a>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-4 font-bold text-gray-700 text-sm">Date</th>
                            <th class="p-4 font-bold text-gray-700 text-sm">Filière</th>
                            <th class="p-4 font-bold text-gray-700 text-sm">Semestre</th>
                            <th class="p-4 font-bold text-gray-700 text-sm">Année</th>
                            <th class="p-4 font-bold text-gray-700 text-sm text-center">Moyenne Générale</th>
                            <th class="p-4 font-bold text-gray-700 text-sm">Statut</th>
                            <th class="p-4 font-bold text-gray-700 text-sm text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pvs as $pv)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="p-4 text-sm text-gray-600">{{ $pv->date_Generation }}</td>
                                <td class="p-4 text-sm font-medium text-gray-800">{{ $pv->filiere->nom_Filiere ?? 'N/A' }}</td>
                                <td class="p-4 text-sm text-gray-600">Semestre {{ $pv->semestre->numero ?? 'N/A' }}</td>
                                <td class="p-4 text-sm text-gray-600">{{ $pv->anneeAcademique->libelle_Annee ?? 'N/A' }}</td>
                                <td class="p-4 text-sm text-gray-800 text-center font-bold">{{ number_format($pv->moyenne_Generale_Classe, 2) }} / 20</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $pv->statut == 'Publié' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                                        {{ $pv->statut }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <button class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        <i class="fas fa-eye mr-1"></i> Détails
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-gray-400 italic">
                                    Aucun PV n'a encore été généré.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
