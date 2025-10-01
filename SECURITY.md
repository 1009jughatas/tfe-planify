# Guide de Sécurité - Planify

## 🔒 Améliorations de Sécurité Implémentées

### 1. **Protection des En-têtes HTTP**

#### Middleware SecurityHeaders
- ✅ **X-Frame-Options** : Protection contre le clickjacking
- ✅ **X-XSS-Protection** : Protection contre les attaques XSS
- ✅ **X-Content-Type-Options** : Empêche le MIME sniffing
- ✅ **Content-Security-Policy** : Contrôle des ressources chargées
- ✅ **Strict-Transport-Security** : Force HTTPS
- ✅ **Referrer-Policy** : Contrôle des informations de référence
- ✅ **Permissions-Policy** : Contrôle des permissions du navigateur

### 2. **Authentification et Autorisation**

#### Rate Limiting
- ✅ Maximum 5 tentatives de connexion par minute
- ✅ Blocage temporaire après dépassement
- ✅ Protection contre les attaques par force brute

#### Contrôle d'Accès
- ✅ **Middleware IsAdmin** : Vérification des rôles administrateur
- ✅ **Vérification des participants** : Accès limité aux membres du projet
- ✅ **Autorisation par ressource** : Vérification pour chaque action (CRUD)
- ✅ **Protection des routes** : Auth + verified middleware

### 3. **Validation des Données**

#### ProjectController
- ✅ Validation stricte des champs (nom, description, dates)
- ✅ Limitation de la longueur des textes (max 5000 caractères)
- ✅ Validation des participants (existence dans la base)
- ✅ Validation des dates (cohérence start_date < end_date)
- ✅ Protection XSS avec `htmlspecialchars()`

#### TaskController
- ✅ Validation des champs (titre, description, priorité)
- ✅ Validation de la priorité (0-3)
- ✅ Vérification de l'assignation (utilisateur dans le projet)
- ✅ Validation des dates (date >= aujourd'hui)
- ✅ Protection XSS avec `htmlspecialchars()`

### 4. **Protection CSRF**

- ✅ **Token CSRF** sur tous les formulaires
- ✅ **Vérification automatique** par Laravel
- ✅ **Régénération des sessions** après login
- ✅ **Invalidation** après logout

### 5. **Gestion des Mots de Passe**

- ✅ **Hachage bcrypt** avec coût 12
- ✅ **Validation complexité** via Rules\Password
- ✅ **Confirmation required** pour changement
- ✅ **Never stored in plain text**

### 6. **Protection XSS (Cross-Site Scripting)**

#### Mesures Implémentées
- ✅ `htmlspecialchars()` sur toutes les entrées utilisateur
- ✅ Blade templates échappent automatiquement `{{ }}`
- ✅ Content Security Policy stricte
- ✅ Validation des données avant stockage

#### Zones Protégées
- ✅ Noms de projets
- ✅ Descriptions de projets et tâches
- ✅ Titres de tâches
- ✅ Commentaires

### 7. **Protection SQL Injection**

- ✅ **Eloquent ORM** : Requêtes paramétrées automatiques
- ✅ **Query Builder** : Protection native
- ✅ **Validation** : Vérification des types de données
- ✅ **Pas de requêtes brutes** dans le code

### 8. **Protection des Données Sensibles**

- ✅ **Fichier .env** : Jamais commité
- ✅ **APP_KEY** : Chiffrement des sessions
- ✅ **Credentials** : Hors du code source
- ✅ **Logs** : Pas de données sensibles

## 🛡️ Bonnes Pratiques Implémentées

### 1. **Principe du Moindre Privilège**
- Les utilisateurs ont accès uniquement à leurs projets
- Les admins ont des droits étendus mais contrôlés
- Vérification d'autorisation sur chaque action sensible

### 2. **Défense en Profondeur**
- Multiples couches de sécurité :
  1. Middleware d'authentification
  2. Middleware de vérification email
  3. Middleware d'autorisation (IsAdmin)
  4. Vérification dans les contrôleurs
  5. Validation des données

### 3. **Sécurité par Défaut**
- Sessions sécurisées (httponly, secure en production)
- CSRF activé par défaut
- Cookies protégés
- Erreurs génériques en production

## ⚠️ Recommandations Supplémentaires

### Pour la Production

1. **Configuration .env**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://votre-domaine.com
   ```

2. **HTTPS Obligatoire**
   - Forcer HTTPS dans `AppServiceProvider`
   - Utiliser un certificat SSL valide
   - Activer HSTS

3. **Mise à Jour Régulière**
   - Dépendances Composer
   - Framework Laravel
   - Packages npm
   - PHP version

4. **Surveillance**
   - Logs d'accès
   - Alertes sur échecs d'authentification
   - Monitoring des erreurs
   - Backups réguliers

5. **Sécurité Base de Données**
   - Utilisateur MySQL dédié avec droits limités
   - Pas de root en production
   - Backups chiffrés
   - Connexions SSL/TLS

## 🔍 Tests de Sécurité

### Tests Automatisés Recommandés
- ✅ Tests d'autorisation (Feature tests)
- ✅ Tests de validation des données
- ✅ Tests de protection CSRF
- ✅ Tests de rate limiting

### Audits Manuels
- Revue de code régulière
- Tests de pénétration
- Scan de vulnérabilités
- Vérification des dépendances

## 📞 Signalement de Vulnérabilités

Si vous découvrez une vulnérabilité de sécurité, veuillez nous contacter :
- Email : security@planify.com
- Ne pas divulguer publiquement avant correction

## 🔄 Changelog Sécurité

### Version 2.0 (Actuelle)
- ✅ Ajout middleware SecurityHeaders
- ✅ Renforcement autorisation ProjectController
- ✅ Renforcement autorisation TaskController
- ✅ Protection XSS améliorée
- ✅ Validation des données stricte
- ✅ Documentation sécurité complète

### Version 1.0
- ✅ Authentification Laravel Breeze
- ✅ Protection CSRF
- ✅ Rate limiting login
- ✅ Middleware IsAdmin

---

**Dernière mise à jour** : 30 septembre 2025  
**Auteur** : Équipe Planify  
**Contact** : security@planify.com
