# Guide Administrateur - Planify

## 👑 Vue d'ensemble

Ce guide détaille toutes les fonctionnalités et responsabilités des administrateurs de Planify.

---

## 🎯 Rôles et Responsabilités

### Supervision Générale
- Surveiller l'activité de la plateforme
- Assurer le bon fonctionnement du système
- Gérer les utilisateurs et leurs permissions
- Maintenir la sécurité de l'application

---

## 🔧 Fonctionnalités Administrateur

### 1. **Panneau d'Administration** (`/admin`)

#### Dashboard Principal
- **Statistiques en temps réel** :
  - Nombre total d'utilisateurs
  - Utilisateurs premium vs gratuits
  - Nombre d'administrateurs
  - Projets actifs/complétés
  - Tâches en cours/terminées
  
- **Utilisateurs récents** : 10 derniers inscrits
- **Projets récents** : 10 derniers créés
- **Activité mensuelle** : Graphique des 6 derniers mois
- **Taux de conversion premium** : Pourcentage calculé automatiquement

#### Routes Admin
```
GET  /admin                    → Dashboard principal
GET  /admin/logs               → Logs système
GET  /admin/statistics         → Statistiques détaillées
GET  /admin/users              → Gestion utilisateurs
GET  /admin/legal              → Gestion contenus légaux
```

---

### 2. **Gestion des Utilisateurs** (`/admin/users`)

#### Liste des Utilisateurs
- **Vue complète** de tous les utilisateurs
- **Filtres** : Par rôle, statut premium, date
- **Recherche** : Par nom ou email
- **Pagination** : 20 utilisateurs par page

#### Actions Disponibles

##### Créer un Utilisateur
```
Route : GET /admin/users/create
Champs : Nom, Email, Mot de passe, Rôle, Statut premium
```

##### Modifier un Utilisateur
```
Route : PATCH /admin/users/{user}
Permissions :
  - Changer nom et email
  - Modifier le rôle (user/admin)
  - Activer/désactiver premium
  - Réinitialiser mot de passe
```

##### Supprimer un Utilisateur
```
Route : DELETE /admin/users/{user}
Restrictions :
  - Ne peut pas supprimer son propre compte
  - Suppression en cascade (projets, tâches, etc.)
```

##### Changer le Rôle
```
Route : POST /admin/users/{user}/change-role
Rôles disponibles : user, admin
Protection : Ne peut pas retirer son propre rôle admin
```

##### Toggle Premium
```
Route : POST /admin/users/{user}/toggle-premium
Action : Active/désactive le statut premium
```

---

### 3. **Gestion des Projets**

#### Accès Complet
- **Voir** tous les projets de tous les utilisateurs
- **Modifier** n'importe quel projet
- **Supprimer** des projets si nécessaire
- **Gérer** les collaborateurs de tous les projets

#### Surveillance
- Projets actifs vs complétés
- Projets sans activité
- Projets avec deadlines dépassées

---

### 4. **Logs et Monitoring** (`/admin/logs`)

#### Logs Système
- **Accès** au fichier `storage/logs/laravel.log`
- **Affichage** des 100 dernières lignes
- **Types de logs** :
  - Erreurs système
  - Échecs d'authentification
  - Accès non autorisés
  - Activités suspectes

#### Événements Surveillés
- Tentatives de connexion échouées
- Accès refusés (403)
- Erreurs de base de données
- Erreurs d'application

---

### 5. **Statistiques Globales** (`/admin/statistics`)

#### Utilisateurs
- Total d'utilisateurs
- Utilisateurs premium
- Utilisateurs gratuits
- Administrateurs
- Utilisateurs vérifiés (email)

#### Projets
- Total de projets
- Projets actifs
- Projets complétés
- Projets annulés
- Moyenne de tâches par projet

#### Tâches
- Total de tâches
- Tâches en attente
- Tâches en cours
- Tâches complétées
- Tâches bloquées
- Tâches en retard

#### Métriques Clés
- **Taux de conversion premium** : % d'utilisateurs premium
- **Taux de complétion** : % de projets terminés
- **Engagement** : Activité mensuelle
- **Performance** : Temps moyen de réalisation

---

### 6. **Gestion Contenus Légaux** (`/admin/legal`)

#### Contenus Modifiables
1. **Mentions Légales**
2. **Conditions Générales d'Utilisation (CGU)**
3. **Politique de Confidentialité**

#### Fonctionnalités
- **Édition** via interface admin
- **Sauvegarde** dans `storage/app/legal/`
- **Prévisualisation** avant publication
- **Historique** des modifications (à venir)

#### Chemins de Stockage
```
storage/app/legal/mentions_legales.txt
storage/app/legal/cgu.txt
storage/app/legal/politique_confidentialite.txt
```

---

### 7. **Sécurité et Maintenance**

#### Surveillance Sécurité
- **Monitoring** des tentatives d'accès non autorisés
- **Alertes** sur activités suspectes
- **Logs** des événements de sécurité
- **Rate limiting** des connexions

#### Mises à Jour
- **Appliquer** les mises à jour Laravel
- **Mettre à jour** les dépendances (Composer, npm)
- **Vérifier** les vulnérabilités
- **Tester** avant déploiement

#### Sauvegardes
- **Base de données** : Backups réguliers
- **Fichiers** : Sauvegarde du storage
- **Configuration** : .env et configs
- **Code** : Git repository

---

## 🛡️ Bonnes Pratiques Admin

### Sécurité
1. **Ne jamais** partager les identifiants admin
2. **Utiliser** des mots de passe forts
3. **Activer** l'authentification à deux facteurs (à venir)
4. **Surveiller** régulièrement les logs
5. **Appliquer** les mises à jour de sécurité

### Gestion des Utilisateurs
1. **Vérifier** l'identité avant accorder rôle admin
2. **Limiter** le nombre d'administrateurs
3. **Documenter** les changements de rôles
4. **Notifier** les utilisateurs des modifications

### Maintenance
1. **Backups quotidiens** de la base de données
2. **Tests réguliers** des fonctionnalités
3. **Surveillance** de la performance
4. **Nettoyage** des logs anciens
5. **Optimisation** de la base de données

---

## 📊 Dashboard Admin

### Sections Principales

#### 1. Vue d'Ensemble
- Cartes statistiques (utilisateurs, projets, tâches)
- Graphiques d'activité
- Indicateurs de performance

#### 2. Gestion Utilisateurs
- Liste complète des utilisateurs
- Actions rapides (edit, delete, toggle premium)
- Statistiques par type d'utilisateur

#### 3. Gestion Projets
- Tous les projets de la plateforme
- Projets nécessitant attention
- Statistiques de complétion

#### 4. Logs et Monitoring
- Logs en temps réel
- Alertes de sécurité
- Erreurs système

#### 5. Contenus Légaux
- Édition des mentions légales
- Mise à jour CGU
- Gestion politique confidentialité

---

## 🚨 Scénarios d'Intervention

### Utilisateur Bloqué
1. Vérifier les logs de connexion
2. Identifier la cause (tentatives échouées)
3. Réinitialiser le mot de passe
4. Notifier l'utilisateur

### Projet Suspect
1. Consulter le projet via `/admin`
2. Vérifier l'activité et les utilisateurs
3. Contacter l'auteur si nécessaire
4. Supprimer si violation des CGU

### Erreur Système
1. Consulter `/admin/logs`
2. Identifier l'erreur
3. Appliquer le correctif
4. Tester le fonctionnement
5. Surveiller les logs

### Mise à Jour Urgente
1. Backup de la base de données
2. Tester en environnement de dev
3. Appliquer la mise à jour
4. Vérifier le fonctionnement
5. Documenter les changements

---

## 🔑 Accès Admin

### Routes Protégées
Toutes les routes admin sont protégées par :
- `auth` : Authentification requise
- `verified` : Email vérifié
- `IsAdmin` : Rôle admin uniquement

### URLs Admin
```
/admin                        → Dashboard
/admin/users                  → Gestion utilisateurs
/admin/users/create          → Créer utilisateur
/admin/users/{id}/edit       → Modifier utilisateur
/admin/logs                   → Logs système
/admin/statistics             → Statistiques globales
/admin/legal                  → Contenus légaux
```

---

## 📞 Support et Escalade

### Niveaux de Support
1. **Utilisateur** → FAQ et documentation
2. **Premium** → Support prioritaire par email
3. **Admin** → Accès direct au système

### Contact Admin
- **Email** : admin@planify.com
- **Urgences** : security@planify.com
- **Support technique** : support@planify.com

---

## 📈 KPIs à Surveiller

### Utilisateurs
- Nombre d'inscriptions/jour
- Taux de conversion premium
- Taux de rétention
- Utilisateurs actifs

### Plateforme
- Nombre de projets créés/jour
- Taux de complétion des projets
- Performance système
- Temps de réponse

### Sécurité
- Tentatives de connexion échouées
- Accès non autorisés
- Erreurs système
- Uptime de l'application

---

**Dernière mise à jour** : 1 octobre 2025  
**Version** : 2.1  
**Responsable** : Équipe Planify
