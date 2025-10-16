# Rôles et Permissions dans Planify

## 📋 Vue d'ensemble

Planify implémente un système de permissions à trois niveaux :
1. **Utilisateur Standard (gratuit)**
2. **Utilisateur Premium (payant)**
3. **Administrateur**

---

## 👤 Utilisateur Standard (Gratuit)

### ✅ Permissions Accordées

#### Projets
- ✅ **Créer** des projets (limité à **3 projets maximum**)
- ✅ **Voir** ses propres projets
- ✅ **Modifier** ses propres projets
- ✅ **Supprimer** ses propres projets
- ❌ **Ne peut PAS** inviter de collaborateurs
- ❌ **Ne peut PAS** voir les statistiques avancées

#### Tâches
- ✅ **Créer** des tâches dans ses projets
- ✅ **Voir** ses tâches
- ✅ **Modifier** ses propres tâches
- ✅ **Supprimer** ses propres tâches
- ✅ **Marquer** une tâche comme terminée
- ❌ **Ne peut PAS** assigner des tâches à d'autres utilisateurs

#### Profil
- ✅ **Consulter** son profil
- ✅ **Mettre à jour** ses informations personnelles
- ✅ **Changer** son mot de passe
- ✅ **Supprimer** son compte

### 🚫 Limitations

- **Projets** : Maximum 3 projets
- **Collaborateurs** : Aucun (travail en solo uniquement)
- **Assignation** : Ne peut pas assigner de tâches
- **Statistiques** : Pas d'accès aux statistiques avancées
- **Export** : Pas d'export PDF

---

## 💎 Utilisateur Premium (Abonné SaaS via Stripe)

### ✅ Permissions Accordées

#### Projets
- ✅ **Créer** des projets (**illimités**)
- ✅ **Voir** ses projets et les projets où il est participant
- ✅ **Modifier** ses propres projets
- ✅ **Supprimer** ses propres projets
- ✅ **Inviter** plusieurs collaborateurs par projet
- ✅ **Voir** les statistiques avancées de ses projets
- ✅ **Stocker et partager** des fichiers dans les projets

#### Tâches
- ✅ **Créer** des tâches illimitées
- ✅ **Voir** toutes ses tâches et tâches assignées
- ✅ **Modifier** ses tâches et les tâches qui lui sont assignées
- ✅ **Supprimer** ses propres tâches
- ✅ **Assigner** des tâches aux collaborateurs
- ✅ **Changer le statut** des tâches du projet
- ✅ **Définir des priorités** sur les tâches
- ✅ **Stocker et partager** des fichiers dans les tâches

#### Fonctionnalités Avancées
- ✅ **Interface moderne** : Organisation visuelle intuitive
- ✅ **Statistiques** : Progression, pourcentage de tâches accomplies
- ✅ **Rapports** : Deadlines, temps moyen de réalisation
- ✅ **Export PDF** : Rapports de projets professionnels
- ✅ **Collaboration** : Travail en équipe avec plusieurs collaborateurs
- ✅ **Calendrier** : Vue calendrier complète
- ✅ **Notifications** : Alertes par email
- ✅ **Rappels automatiques** : Email avant les deadlines
- ✅ **Stockage de fichiers** : Upload et partage de documents
- ✅ **Personnalisation** : Mode sombre, choix de couleurs, thème

#### Personnalisation de l'Interface
- ✅ **Mode sombre** : Activation/désactivation
- ✅ **Choix de couleurs** : 8 thèmes de couleurs disponibles
- ✅ **Langue** : Français, English, Nederlands
- ✅ **Format de date** : Personnalisable
- ✅ **Fuseau horaire** : Configurable
- ✅ **Notifications email** : Activable/désactivable
- ✅ **Rappels automatiques** : Configurable (1-168 heures avant)

#### Profil
- ✅ **Badge Premium** : Affichage du statut premium
- ✅ Toutes les permissions utilisateur standard
- ✅ **Préférences** : Interface personnalisée

### 🎯 Avantages

- **Projets illimités**
- **Collaboration d'équipe**
- **Assignation de tâches**
- **Statistiques et rapports**
- **Export PDF**

---

## 👑 Administrateur

### ✅ Permissions Accordées

#### Toutes les Fonctionnalités Premium
- ✅ **Dispose de toutes** les permissions Premium
- ✅ **Projets illimités** et fonctionnalités avancées
- ✅ **Interface moderne**, statistiques, export PDF
- ✅ **Personnalisation** complète de l'interface

#### Projets
- ✅ **Créer** tous types de projets (**illimités**)
- ✅ **Voir** TOUS les projets (système entier)
- ✅ **Modifier** TOUS les projets
- ✅ **Supprimer** TOUS les projets (si nécessaire)
- ✅ **Gérer** les collaborateurs de tous les projets
- ✅ **Accès total** aux statistiques de tous les projets

#### Tâches
- ✅ **Créer** des tâches dans tous les projets
- ✅ **Voir** TOUTES les tâches
- ✅ **Modifier** TOUTES les tâches
- ✅ **Supprimer** TOUTES les tâches
- ✅ **Assigner** des tâches à n'importe qui
- ✅ **Changer** le statut de toutes les tâches

#### Gestion des Utilisateurs
- ✅ **Créer** de nouveaux utilisateurs
- ✅ **Voir** tous les utilisateurs (liste complète)
- ✅ **Modifier** les informations de tous les utilisateurs
- ✅ **Supprimer** des utilisateurs
- ✅ **Changer les rôles** : user/premium/admin
- ✅ **Activer/Désactiver** le statut premium
- ✅ **Réinitialiser** les mots de passe
- ✅ **Voir** l'historique d'activité des utilisateurs

#### Logs et Monitoring
- ✅ **Accès aux logs** système (laravel.log)
- ✅ **Statistiques globales** de la plateforme
- ✅ **Monitoring** de l'activité
- ✅ **Analyse** des performances
- ✅ **Surveillance** de la sécurité

#### Contenus Légaux
- ✅ **Gérer** les mentions légales
- ✅ **Gérer** les CGU (Conditions Générales d'Utilisation)
- ✅ **Gérer** la politique de confidentialité
- ✅ **Modifier** les contenus via l'interface admin
- ✅ **Publier** les mises à jour légales

#### Panneau d'Administration
- ✅ **Dashboard admin** complet
- ✅ **Vue d'ensemble** du système
- ✅ **Statistiques** en temps réel
- ✅ **Gestion centralisée** de toutes les ressources
- ✅ **Outils de supervision**

#### Sécurité et Maintenance
- ✅ **Surveiller** la sécurité de l'application
- ✅ **Appliquer** des mises à jour
- ✅ **Gérer** les sauvegardes
- ✅ **Résoudre** les problèmes techniques
- ✅ **Configuration** avancée du système

---

## 🔐 Matrice des Permissions

| Action | Utilisateur Gratuit | Utilisateur Premium | Administrateur |
|--------|---------------------|---------------------|----------------|
| **Projets** |
| Créer un projet | ✅ (max 3) | ✅ (illimité) | ✅ (illimité) |
| Voir ses projets | ✅ | ✅ | ✅ (tous) |
| Modifier son projet | ✅ | ✅ | ✅ (tous) |
| Supprimer son projet | ✅ | ✅ | ✅ (tous) |
| Inviter des collaborateurs | ❌ | ✅ | ✅ |
| Voir statistiques | ❌ | ✅ | ✅ |
| **Tâches** |
| Créer une tâche | ✅ | ✅ | ✅ |
| Voir ses tâches | ✅ | ✅ | ✅ (toutes) |
| Modifier sa tâche | ✅ | ✅ + assignées | ✅ (toutes) |
| Supprimer sa tâche | ✅ | ✅ | ✅ (toutes) |
| Assigner une tâche | ❌ | ✅ | ✅ |
| Changer le statut | ✅ (ses tâches) | ✅ (projet) | ✅ (toutes) |
| **Fonctionnalités** |
| Export PDF | ❌ | ✅ | ✅ |
| Calendrier avancé | ❌ | ✅ | ✅ |
| Notifications | ❌ | ✅ | ✅ |
| Collaboration | ❌ | ✅ | ✅ |

---

## 🛡️ Implémentation Technique

### Policies Laravel

#### ProjectPolicy
```php
- viewAny(User $user): Voir la liste
- view(User $user, Project $project): Voir un projet
- create(User $user): Créer (avec limite 3 pour gratuit)
- update(User $user, Project $project): Modifier (auteur ou admin)
- delete(User $user, Project $project): Supprimer (auteur ou admin)
- inviteCollaborators(User $user, Project $project): Inviter (premium uniquement)
- viewStatistics(User $user, Project $project): Stats (premium uniquement)
```

#### TaskPolicy
```php
- viewAny(User $user): Voir la liste
- view(User $user, Task $task): Voir une tâche
- create(User $user): Créer
- update(User $user, Task $task): Modifier (auteur ou assigné si premium)
- delete(User $user, Task $task): Supprimer (auteur uniquement)
- updateStatus(User $user, Task $task): Changer statut
- assign(User $user, Task $task): Assigner (premium uniquement)
```

### Middleware

#### IsAdmin
- Vérifie que l'utilisateur a le rôle 'admin'
- Redirige vers la page d'accueil si non autorisé

#### SecurityHeaders
- Ajoute les en-têtes de sécurité HTTP
- Protection XSS, CSRF, Clickjacking

---

## 🔄 Workflow Utilisateur

### Utilisateur Gratuit
1. **S'inscrit** → Compte créé
2. **Se connecte** → Accès dashboard basique
3. **Crée projet 1** → ✅ Autorisé
4. **Crée projet 2** → ✅ Autorisé
5. **Crée projet 3** → ✅ Autorisé
6. **Crée projet 4** → ❌ Refusé (limite atteinte)
7. **Tente d'inviter** → ❌ Fonctionnalité premium

### Utilisateur Premium
1. **S'inscrit** → Compte créé
2. **Souscrit premium** → Compte upgradé
3. **Crée projets** → ✅ Illimité
4. **Invite collaborateurs** → ✅ Autorisé
5. **Assigne tâches** → ✅ Autorisé
6. **Export PDF** → ✅ Autorisé
7. **Voir stats** → ✅ Autorisé

### Administrateur
1. **Se connecte** → Accès total
2. **Voit tous les projets** → ✅ Vue globale
3. **Gère tous les utilisateurs** → ✅ Contrôle total
4. **Modifie/Supprime tout** → ✅ Droits complets

---

## 📝 Messages d'Erreur

### Limitations Gratuit
- "Vous avez atteint la limite de projets (3). Passez en premium pour créer plus de projets."
- "La fonctionnalité d'invitation de collaborateurs est réservée aux utilisateurs premium."
- "La fonctionnalité d'assignation de tâches est réservée aux utilisateurs premium."
- "Les statistiques avancées sont réservées aux utilisateurs premium."

### Erreurs d'Autorisation
- "Accès non autorisé à ce projet."
- "Accès non autorisé à cette tâche."
- "Seuls les administrateurs et l'auteur peuvent modifier/supprimer."

---

## 🚀 Upgrade vers Premium

### Fonctionnalités Débloquées
1. **Projets illimités**
2. **Invitation de collaborateurs**
3. **Assignation de tâches**
4. **Statistiques avancées**
5. **Export PDF**
6. **Calendrier complet**
7. **Notifications**
8. **Support prioritaire**

### Process d'Upgrade
1. Accès à `/premium`
2. Sélection du plan
3. Paiement sécurisé (Stripe)
4. Activation instantanée
5. Accès immédiat à toutes les fonctionnalités

---

## 🔍 Tests de Permissions

### Tests Automatisés Recommandés

```php
// Test limite projets gratuit
public function test_free_user_cannot_create_more_than_3_projects()

// Test invitation premium
public function test_only_premium_can_invite_collaborators()

// Test assignation premium
public function test_only_premium_can_assign_tasks()

// Test autorisation admin
public function test_admin_can_access_all_projects()
```

---

**Dernière mise à jour** : 30 septembre 2025  
**Version** : 2.0  
**Système de permissions** : Laravel Policies + Custom Logic
