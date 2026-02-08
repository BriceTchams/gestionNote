<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 20px; }
        .result-box { background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f3f4f6; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
        .status-admis { color: #2f855a; font-weight: bold; }
        .status-rattrapage { color: #c05621; font-weight: bold; }
        .status-ajourne { color: #c53030; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #1a365d; margin: 0;">Université de Douala</h2>
            <p style="margin: 5px 0;">ENSP de Douala</p>
        </div>

        <p>Bonjour <strong>{{ $resultat['etudiant']->nom }}</strong>,</p>

        <p>Les résultats pour le <strong>Semestre {{ $pvData['semestre']->numero }}</strong> (Année Académique : {{ $pvData['semestre']->anneeAcademique->libelle_Annee }}) ont été publiés.</p>

        <div class="result-box">
            <h3 style="margin-top: 0; color: #1a365d;">Résumé de vos performances :</h3>
            <ul style="list-style: none; padding: 0;">
                <li><strong>Moyenne Générale :</strong> {{ $resultat['moyenne'] }} / 20</li>
                <li><strong>Crédits Validés :</strong> {{ $resultat['credits_valides'] }}</li>
                <li><strong>Décision :</strong>
                    <span class="status-{{ strtolower($resultat['statut']) }}">
                        {{ $resultat['statut'] }}
                    </span>
                </li>
            </ul>
        </div>

        <h3 style="color: #1a365d;">Détail par Unité d'Enseignement :</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>UE</th>
                    <th>Code</th>
                    <th>Note Finale</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultat['groupes'] as $groupe)
                    @foreach($groupe['ues'] as $ue)
                        <tr>
                            <td>{{ $ue['ue']->libelle }}</td>
                            <td>{{ $ue['ue']->code }}</td>
                            <td>{{ $ue['finale'] }}</td>
                            <td>{{ $ue['statut'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <p>Vous pouvez consulter le PV officiel complet au département.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} UniNotes - Système de Gestion des Notes</p>
            <p>Ce mail a été généré automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
