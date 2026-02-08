<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PV {{ $filiere->nom_Filiere }} - {{ $classe->lib_Classe }}</title>
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 9px; color: #333; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .header-table td { border: none; }
        .header-bottom-line { border-bottom: 3px solid #1a365d !important; }
        .header-left, .header-right { width: 35%; line-height: 1.4; font-weight: bold; }
        .header-left { text-align: center; }
        .header-center { width: 30%; text-align: center; vertical-align: middle; }
        .header-right { text-align: center; }
        .logo { width: 70px; height: auto; }

        .institution-name { font-size: 11px; font-weight: bold; color: #1a365d; }
        .separator { margin: 3px 0; color: #1a365d; }

        .title-section { text-align: center; margin-bottom: 15px; background-color: #f8fafc; padding: 10px; border: 1px solid #e2e8f0; }
        .title-main { font-size: 14px; font-weight: bold; color: #1a365d; text-decoration: none; text-transform: uppercase; margin-bottom: 5px; }
        .title-sub { font-size: 11px; color: #4a5568; }

        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; background-color: #edf2f7; border: 2px solid #cbd5e0; }
        .info-grid td { padding: 8px; border: 2px solid #cbd5e0 !important; font-weight: bold; font-size: 10px; }
        .label { color: #4a5568; font-size: 8px; text-transform: uppercase; }

        .pv-table { width: 100%; border-collapse: collapse; table-layout: fixed; border: 2px solid #2d3748; }
        .pv-table th, .pv-table td { border: 1px solid #2d3748 !important; padding: 4px 2px; text-align: center; word-wrap: break-word; }
        .pv-table th { color: #1a365d; font-weight: bold; font-size: 8px; background-color: #e2e8f0; }
        .pv-table tr:nth-child(even) { background-color: #f7fafc; }
        .pv-table tbody tr { border-bottom: 1px solid #2d3748 !important; }

        .col-matricule { width: 60px; }
        .col-nom { width: 140px; text-align: left !important; padding-left: 5px !important; }
        .col-credits { width: 45px; background-color: #ebf8ff; font-weight: bold; }
        .col-moy { width: 40px; font-weight: bold; background-color: #fffaf0; }
        .col-statut { width: 45px; font-weight: bold; }

        .group-header { color: #1a365d !important; }
        .ue-code-header { color: #1a365d; font-size: 7px; height: 35px; }

        .footer { margin-top: 20px; width: 100%; }
        .stats-table { border: 2px solid #2d3748 !important; border-collapse: collapse; width: auto; background-color: #f8fafc; }
        .stats-table td { padding: 5px 15px; border: 2px solid #2d3748 !important; }
        .stats-label { font-weight: bold; color: #4a5568; }
        .stats-value { font-weight: bold; color: #1a365d; font-size: 11px; }

        .signature-area { margin-top: 25px; }
        .signature-box { text-align: center; float: right; width: 250px; }
        .signature-title { font-weight: bold; text-decoration: underline; font-size: 11px; }
        .signature-space { margin-top: 50px; font-style: italic; color: #a0aec0; }

        .text-v { color: #2f855a; font-weight: bold; }
        .text-vc { color: #c05621; font-weight: bold; }
        .text-nv { color: #c53030; font-weight: bold; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-left">
                <span class="institution-name">REPUBLIQUE DU CAMEROUN</span><br>
                Paix - Travail - Patrie<br>
                <div class="separator">----------</div>
                <span class="institution-name">UNIVERSITE DE DOUALA</span><br>
                B.P. 2701 Douala
            </td>
            <td class="header-center">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('image/logo.png'))) }}" class="logo" alt="Logo">
            </td>
            <td class="header-right">
                <span class="institution-name">REPUBLIC OF CAMEROON</span><br>
                Peace - Work - Fatherland<br>
                <div class="separator">----------</div>
                <span class="institution-name">ENSP DE DOUALA</span><br>
                Ecole Nationale Supérieure Polytechnique
            </td>
        </tr>
        <tr>
            <td colspan="3" class="header-bottom-line"></td>
        </tr>
    </table>

    <div class="title-section">
        <div class="title-main">
            PROCES VERBAL {{ $isRattrapage ? 'DE RATTRAPAGE' : 'SEMESTRIEL' }} DES NOTES
        </div>
        <div class="title-sub">SESSION : {{ $isRattrapage ? 'RATTRAPAGE' : 'NORMALE' }} | ANNEE ACADEMIQUE : {{ $semestre->anneeAcademique->libelle_Annee ?? 'N/A' }}</div>
    </div>

    <table class="info-grid">
        <tr>
            <td><span class="label">Filière :</span><br>{{ $filiere->nom_Filiere }}</td>
            <td><span class="label">Classe :</span><br>{{ $classe->lib_Classe }}</td>
            <td><span class="label">Semestre :</span><br>Semestre {{ $semestre->numero }}</td>
        </tr>
    </table>

    <table class="pv-table">
        <thead>
            <tr>
                <th rowspan="2" class="col-matricule">Matricule</th>
                <th rowspan="2" class="col-nom">Noms et Prénoms</th>
                @foreach($groupesUe as $groupe)
                    <th colspan="{{ $groupe->ues->count() }}" class="group-header">{{ $groupe->code }}</th>
                    <th rowspan="2" class="group-header">Moy {{ $groupe->code }}</th>
                    <th rowspan="2" class="group-header">Déc.</th>
                @endforeach
                <th rowspan="2" class="col-credits">Crédits</th>
                <th rowspan="2" class="col-moy">MG</th>
                <th rowspan="2" class="col-statut">Résultat</th>
            </tr>
            <tr>
                @foreach($groupesUe as $groupe)
                    @foreach($groupe->ues as $ue)
                        <th class="ue-code-header">{{ $ue->code }}<br>({{ $ue->credits }})</th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($resultats as $resultat)
                <tr>
                    <td>{{ $resultat['etudiant']->matricule }}</td>
                    <td class="col-nom">{{ $resultat['etudiant']->nom }}</td>
                    @foreach($resultat['groupes'] as $gRes)
                        @foreach($gRes['ues'] as $ueRes)
                            <td class="text-{{ strtolower($ueRes['statut']) }}">
                                {{ $ueRes['finale'] }}<br>({{ $ueRes['statut'] }})
                            </td>
                        @endforeach
                        <td>{{ $gRes['moyenne'] }}</td>
                        <td>{{ $gRes['decision'] == 'Validé' ? 'V' : 'NV' }}</td>
                    @endforeach
                    <td class="col-credits">{{ $resultat['credits_valides'] }}</td>
                    <td class="col-moy">{{ $resultat['moyenne'] }}</td>
                    <td>{{ $resultat['statut'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="stats-table">
            <tr>
                <td><span class="stats-label">Effectif :</span> <span class="stats-value">{{ count($resultats) }}</span></td>
                <td><span class="stats-label">Taux de réussite :</span> <span class="stats-value">{{ count($resultats) > 0 ? round((collect($resultats)->where('moyenne', '>=', 10)->count() / count($resultats)) * 100, 2) : 0 }} %</span></td>
                <td><span class="stats-label">Moyenne générale :</span> <span class="stats-value">{{ count($resultats) > 0 ? round(collect($resultats)->avg('moyenne'), 2) : 0 }} / 20</span></td>
            </tr>
        </table>

        <div class="signature-area">
            <div class="signature-box">
                Fait à Douala, le {{ $dateGeneration }}<br><br>
                <span class="signature-title">Le Chef de Département</span><br>
                <div class="signature-space">(Signature et Cachet)</div>
            </div>
        </div>
    </div>
</body>
</html>
