<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procès-Verbal - {{ $filiere->nom }} - {{ $classe->nom }} - {{ $semestre->nom }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .university {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .faculty {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #2980b9;
            margin: 15px 0;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .info-table th, .info-table td {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        .info-table th {
            background-color: #e9ecef;
            font-weight: bold;
            width: 25%;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        .results-table thead {
            background-color: #2c3e50;
            color: white;
        }
        .results-table th, .results-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .results-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .results-table tbody tr:hover {
            background-color: #e9ecef;
        }
        .status-admis {
            color: #27ae60;
            font-weight: bold;
        }
        .status-rattrapage {
            color: #f39c12;
            font-weight: bold;
        }
        .status-ajourne {
            color: #e74c3c;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 300px;
            margin-left: auto;
            margin-top: 40px;
        }
        .page-break {
            page-break-before: always;
        }
        .statistics {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #2980b9;
        }
        .statistics h4 {
            margin-top: 0;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="university">Université des Sciences et Technologies</div>
        <div class="faculty">Faculté des Sciences de l'Ingénieur</div>
        <div class="faculty">Département d'Informatique</div>
        <div class="title">PROCÈS-VERBAL DES NOTES</div>
    </div>

    <table class="info-table">
        <tr>
            <th>Filière</th>
            <td>{{ $filiere->nom_Filiere }}</td>
            <th>Classe</th>
            <td>{{ $classe->lib_Classe }}</td>
        </tr>
        <tr>
            <th>Semestre</th>
            <td>Semestre {{ $semestre->numero }}</td>
            <th>Date de génération</th>
            <td>{{ $dateGeneration }}</td>
        </tr>
        <tr>
            <th>Nombre d'étudiants</th>
            <td>{{ count($resultats) }}</td>
            <th>Nombre d'évaluations</th>
            <td>{{ $evaluations->count() }}</td>
        </tr>
    </table>

    <h3>Liste des évaluations</h3>
    <ul>
        @foreach($evaluations as $evaluation)
            <li>{{ $evaluation->type_Evaluation }} - {{ $evaluation->date_Evaluation->format('d/m/Y') }}</li>
        @endforeach
    </ul>

    <h3>Résultats des étudiants</h3>
    <table class="results-table">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Matricule</th>
                <th>Nom et Prénom</th>
                @foreach($evaluations as $evaluation)
                    <th>{{ substr($evaluation->type_Evaluation, 0, 10) }}</th>
                @endforeach
                <th>Moyenne</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultats as $index => $resultat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $resultat['etudiant']->matricule ?? 'N/A' }}</td>
                    <td>{{ $resultat['etudiant']->nom }} {{ $resultat['etudiant']->prenom }}</td>
                    @foreach($evaluations as $evaluation)
                        @php
                            $note = $resultat['notes']->where('id_Evaluation', $evaluation->id_Evaluation)->first();
                        @endphp
                        <td>{{ $note ? $note->valeur : 'Abs' }}</td>
                    @endforeach
                    <td><strong>{{ $resultat['moyenne'] }}</strong></td>
                    <td class="status-{{ strtolower($resultat['statut']) }}">{{ $resultat['statut'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="statistics">
        <h4>Statistiques de la promotion</h4>
        @php
            $admis = collect($resultats)->where('statut', 'Admis')->count();
            $rattrapage = collect($resultats)->where('statut', 'Rattrapage')->count();
            $ajourne = collect($resultats)->where('statut', 'Ajourné')->count();
            $total = count($resultats);
            $pourcentageAdmis = $total > 0 ? round(($admis / $total) * 100, 1) : 0;
        @endphp
        <p><strong>Admis:</strong> {{ $admis }} ({{ $pourcentageAdmis }}%) | 
           <strong>Rattrapage:</strong> {{ $rattrapage }} | 
           <strong>Ajourné:</strong> {{ $ajourne }} | 
           <strong>Total:</strong> {{ $total }}</p>
        <p><strong>Moyenne générale:</strong> {{ $total > 0 ? round(collect($resultats)->avg('moyenne'), 2) : 0 }}</p>
    </div>

    <div class="signature">
        <p>Le Chef de Département,</p>
        <div class="signature-line"></div>
        <p>Dr. Martin Leroy</p>
        <p>Chef du Département d'Informatique</p>
        <p>Date: {{ $dateGeneration }}</p>
    </div>

    <div class="footer">
        <p>Document généré électroniquement - Université des Sciences et Technologies</p>
        <p>Ce document a une valeur officielle</p>
    </div>
</body>
</html>