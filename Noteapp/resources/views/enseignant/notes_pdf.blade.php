<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes {{ $ue->code }} - {{ $evaluation->type_Evaluation }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-left, .header-right { width: 40%; text-align: center; }
        .header-center { width: 20%; text-align: center; vertical-align: middle; }
        .logo { width: 80px; height: auto; }
        .institution { font-weight: bold; font-size: 12px; color: #1a365d; }
        .separator { margin: 5px 0; color: #1a365d; }

        .header-bottom-line { border-bottom: 3px solid #1a365d; margin-bottom: 20px; }

        .title-section { text-align: center; margin-bottom: 25px; background-color: #f8fafc; padding: 15px; border: 1px solid #e2e8f0; }
        .title-main { font-size: 16px; font-weight: bold; color: #1a365d; text-transform: uppercase; margin-bottom: 5px; }
        .title-sub { font-size: 12px; color: #4a5568; }

        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-grid td { padding: 8px; border: 1px solid #cbd5e0; background-color: #f1f5f9; }
        .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 11px; font-weight: bold; color: #1e293b; }

        .notes-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .notes-table th, .notes-table td { border: 1px solid #cbd5e0; padding: 10px 8px; text-align: left; }
        .notes-table th { background-color: #f8fafc; color: #1a365d; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .notes-table tr:nth-child(even) { background-color: #fdfdfe; }

        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }

        .footer { margin-top: 40px; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-title { font-weight: bold; text-decoration: underline; margin-bottom: 60px; display: block; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-left">
                <span class="institution">REPUBLIQUE DU CAMEROUN</span><br>
                Paix - Travail - Patrie<br>
                <div class="separator">----------</div>
                <span class="institution">UNIVERSITE DE DOUALA</span>
            </td>
            <td class="header-center">
                @php
                    $logoPath = public_path('image/logo.png');
                    $logoData = "";
                    if (file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" class="logo">
                @endif
            </td>
            <td class="header-right">
                <span class="institution">REPUBLIC OF CAMEROON</span><br>
                Peace - Work - Fatherland<br>
                <div class="separator">----------</div>
                <span class="institution">ENSP DE DOUALA</span>
            </td>
        </tr>
    </table>
    <div class="header-bottom-line"></div>

    <div class="title-section">
        <div class="title-main">FICHE DE NOTES : {{ $evaluation->type_Evaluation }}</div>
        <div class="title-sub">Année Académique : {{ $evaluation->semestre->anneeAcademique->libelle_Annee ?? 'N/A' }} | Semestre {{ $evaluation->semestre->numero }}</div>
    </div>

    <table class="info-grid">
        <tr>
            <td width="33%"><span class="label">Filière</span><br><span class="value">{{ $filiere->nom_Filiere }}</span></td>
            <td width="33%"><span class="label">Classe</span><br><span class="value">{{ $classe->lib_Classe }}</span></td>
            <td width="34%"><span class="label">Matière (UE)</span><br><span class="value">{{ $ue->code }} - {{ $ue->libelle }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Enseignant</span><br><span class="value">{{ $enseignant->nom_Enseignant }}</span></td>
            <td><span class="label">Date d'évaluation</span><br><span class="value">{{ \Carbon\Carbon::parse($evaluation->date_Evaluation)->format('d/m/Y') }}</span></td>
            <td><span class="label">Crédits</span><br><span class="value">{{ $ue->credits }}</span></td>
        </tr>
    </table>

    <table class="notes-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="20%">Matricule</th>
                <th width="55%">Noms et Prénoms</th>
                <th width="20%" class="text-center">Note / 20</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $index => $etudiant)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $etudiant->matricule_Et }}</td>
                <td>{{ $etudiant->nom_Complet }}</td>
                <td class="text-center font-bold" style="font-size: 13px;">
                    {{ isset($notes[$etudiant->id_Etudiant]) ? number_format($notes[$etudiant->id_Etudiant], 2) : '---' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            Fait à Douala, le {{ $date }}<br><br>
            <span class="signature-title">L'Enseignant</span>
            <br><br><br>
            <span class="font-bold">{{ $enseignant->nom_Enseignant }}</span>
        </div>
    </div>
</body>
</html>
