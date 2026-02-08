<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #1a365d; padding-bottom: 10px; margin-bottom: 20px; }
        .note-box { background: #f0f7ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #cce3ff; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #1a365d; margin: 0;">UniNotes</h2>
            <p style="margin: 5px 0;">Notification de Résultats</p>
        </div>

        <p>Bonjour <strong>{{ $etudiant->nom_Complet }}</strong>,</p>

        <p>Votre enseignant a généré la fiche de notes pour l'évaluation suivante :</p>

        <div class="note-box">
            <h3 style="margin-top: 0; color: #1a365d;">{{ $ue->libelle }}</h3>
            <p style="margin: 5px 0;">Type d'évaluation : <strong>{{ $evaluation->type_Evaluation }}</strong></p>
            <p style="font-size: 18px; margin: 10px 0;">Votre Note : <strong style="color: #0d6efd;">{{ $note !== null ? number_format($note, 2) : '---' }} / 20</strong></p>
        </div>

        <p>Vous pouvez consulter le détail de vos notes et télécharger la fiche officielle dans votre espace étudiant, onglet <strong>"Mes Notes"</strong>.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} UniNotes - Système de Gestion des Notes</p>
            <p>Ce mail a été généré automatiquement par UniNotes.</p>
        </div>
    </div>
</body>
</html>
