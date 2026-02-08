<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Étudiants</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .header h1 { color: #4f46e5; margin-bottom: 5px; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 5px 0; }
        table.main { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main th, table.main td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        table.main th { bg-color: #f8f9fa; font-weight: bold; text-transform: uppercase; font-size: 10px; color: #666; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #aaa; }
        .badge { background-color: #eef2ff; color: #4338ca; padding: 2px 8px; border-radius: 10px; font-weight: bold; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UniNotes</h1>
        <p>Liste Officielle des Étudiants</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Filière :</strong> {{ $classe ? ($classe->filiere->nom_Filiere ?? 'Toutes') : 'Toutes' }}</td>
                <td style="text-align: right;"><strong>Date :</strong> {{ $date }}</td>
            </tr>
            <tr>
                <td><strong>Classe :</strong> {{ $classe ? $classe->lib_Classe : 'Toutes' }}</td>
                <td style="text-align: right;"><strong>Nombre d'étudiants :</strong> {{ count($etudiants) }}</td>
            </tr>
        </table>
    </div>

    <table class="main">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Matricule</th>
                <th style="width: 30%;">Nom Complet</th>
                <th style="width: 15%;">Login</th>
                <th style="width: 15%;">Password</th>
                <th style="width: 20%;">Filière</th>
            </tr>
        </thead>
        <tbody>
            @forelse($etudiants as $index => $etudiant)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge">{{ $etudiant->matricule_Et }}</span></td>
                    <td><strong>{{ $etudiant->nom_Complet }}</strong></td>
                    <td>{{ $etudiant->login }}</td>
                    <td>{{ $etudiant->add_plain_password }}</td>
                    <td>{{ $etudiant->classe->filiere->nom_Filiere ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Aucun étudiant trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ $date }} par UniNotes
    </div>
</body>
</html>
