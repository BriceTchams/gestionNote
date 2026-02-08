# Documentation Complète du Projet UniNotes

## 1. Présentation du Projet
**UniNotes** est une plateforme web de gestion académique conçue pour automatiser et sécuriser le processus de gestion des notes au sein d'un établissement universitaire. Le système facilite l'interaction entre les trois acteurs principaux : le Département, les Enseignants et les Étudiants.

## 2. Architecture Technique
- **Framework :** Laravel 11+
- **Langage :** PHP 8.3
- **Base de données :** MySQL
- **Interface :** Tailwind CSS & Lucide Icons (Design moderne et responsive)
- **Authentification :** Système de Multi-Guards (Séparation des espaces Etudiant, Enseignant, Departement).
- **Génération de documents :** Barryvdh/DomPDF pour les rapports officiels.
- **Communication :** Système de notifications par Email (SMTP).

## 3. Modules et Fonctionnalités

### A. Espace Département (Administration)
Le Chef de Département supervise l'ensemble du processus académique :
- **Gestion des structures :** Création et modification des filières, classes et groupes d'Unités d'Enseignement (UE).
- **Gestion des comptes :** Inscription des étudiants et des enseignants avec génération automatique des identifiants (Login/Mot de passe).
- **Publication des PV :** Génération officielle des Procès-Verbaux de délibération (Session Normale et Rattrapage).
- **Notifications :** Envoi automatique des résultats par email à toute une classe lors de la publication du PV.
- **Statistiques :** Visualisation de la répartition des étudiants et taux de réussite.

### B. Espace Enseignant (Pédagogie)
L'enseignant gère les évaluations des matières qui lui sont assignées :
- **Gestion des Évaluations :** Création d'épreuves (CC, Examen, TP, Rattrapage) par semestre.
- **Saisie des Notes :** Interface de saisie rapide par classe avec filtrage dynamique.
- **Fiches de Notes :** Génération de PDF officiels pour chaque évaluation.
- **Traitement des Revendications :** Réponse aux réclamations des étudiants (Acceptation/Rectification ou Rejet).

### C. Espace Étudiant (Consultation)
L'étudiant suit son parcours en temps réel :
- **Consultation des Notes :** Accès aux notes individuelles dès leur saisie par l'enseignant.
- **Téléchargement :** Récupération des fiches de notes et des PV de délibération officiels en PDF.
- **Revendications :** Possibilité de contester une note en envoyant un message à l'enseignant concerné.
- **Tableau de Bord :** Vue d'ensemble des dernières notes et du statut des réclamations.

## 4. Fonctionnalités Clés et Logique de Code

### Système de Calcul des Notes (Logique 30/70)
Le système applique automatiquement les poids réglementaires :
- **CC :** 30%
- **Examen / Rattrapage :** 70%
*Extrait PvController.php :*
```php
$noteFinale = ($noteCC * 0.3) + ($noteExamen * 0.7);
if ($noteRattrapage !== null) {
    $noteFinaleRattrapage = ($noteCC * 0.3) + ($noteRattrapage * 0.7);
    if ($noteFinaleRattrapage > $noteFinale) {
        $noteFinale = $noteFinaleRattrapage;
    }
}
```

### Système de Revendication (Auto-guérison de la DB)
Pour assurer la robustesse, un mécanisme vérifie et corrige la structure de la base de données dynamiquement.
*Extrait EtudiantController.php :*
```php
if (!Schema::hasColumn('revendications', 'id_Evaluation')) {
    Schema::table('revendications', function (Blueprint $table) {
        $table->foreignId('id_Evaluation')->constrained('evaluations');
    });
}
```

### Génération de PDF Professionnels
Utilisation de templates Blade dédiés pour un rendu identique aux documents administratifs officiels, incluant les logos, les entêtes et les tableaux calculés dynamiquement.

## 5. Étapes de Création des Fonctionnalités
1. **Modélisation :** Conception de la base de données (Filières -> Classes -> Étudiants / UEs -> Évaluations -> Notes).
2. **Authentification :** Mise en place des gardes personnalisés pour chaque type d'utilisateur.
3. **Mise en page :** Création des Sidebars et Headers harmonisés avec Tailwind CSS.
4. **Logique Métier :** Développement des contrôleurs pour la saisie des notes et la génération des PV.
5. **Communication :** Intégration de Mailtrap/Gmail pour l'envoi des mails.
6. **Optimisation :** Ajout des tris alphabétiques et des compteurs de notifications.

---
*Ce document résume l'état actuel du projet UniNotes au 08 Février 2026.*
