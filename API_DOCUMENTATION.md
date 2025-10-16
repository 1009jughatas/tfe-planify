# 📚 Documentation API Planify

## 🔐 Authentification

### Connexion
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

**Réponse :**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "user_independant",
        "company": null
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
}
```

### Déconnexion
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

### Utilisateur connecté
```http
GET /api/v1/auth/user
Authorization: Bearer {token}
```

---

## 👥 Utilisateurs

### Lister les utilisateurs
```http
GET /api/v1/users
Authorization: Bearer {token}
```

### Créer un utilisateur
```http
POST /api/v1/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "user_entreprise",
    "company_id": 1,
    "position": "Développeur",
    "department": "IT"
}
```

### Voir un utilisateur
```http
GET /api/v1/users/{id}
Authorization: Bearer {token}
```

### Modifier un utilisateur
```http
PUT /api/v1/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Doe Updated",
    "position": "Senior Developer"
}
```

### Supprimer un utilisateur
```http
DELETE /api/v1/users/{id}
Authorization: Bearer {token}
```

### Profil utilisateur
```http
GET /api/v1/users/profile
Authorization: Bearer {token}
```

### Mettre à jour le profil
```http
PUT /api/v1/users/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "New Name",
    "position": "New Position"
}
```

---

## 📋 Projets

### Lister les projets
```http
GET /api/v1/projects
Authorization: Bearer {token}
```

### Créer un projet
```http
POST /api/v1/projects
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Mon Projet",
    "description": "Description du projet",
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "priority": "high",
    "status": "pending"
}
```

### Voir un projet
```http
GET /api/v1/projects/{id}
Authorization: Bearer {token}
```

### Modifier un projet
```http
PUT /api/v1/projects/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Projet Modifié",
    "status": "in-progress"
}
```

### Supprimer un projet
```http
DELETE /api/v1/projects/{id}
Authorization: Bearer {token}
```

### Statistiques d'un projet
```http
GET /api/v1/projects/{id}/stats
Authorization: Bearer {token}
```

**Réponse :**
```json
{
    "total_tasks": 10,
    "completed_tasks": 5,
    "in_progress_tasks": 3,
    "pending_tasks": 2,
    "overdue_tasks": 1,
    "completion_percentage": 50.0
}
```

---

## ✅ Tâches

### Lister les tâches
```http
GET /api/v1/tasks
Authorization: Bearer {token}
```

### Créer une tâche
```http
POST /api/v1/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Ma Tâche",
    "description": "Description de la tâche",
    "due_date": "2024-01-15",
    "priority": "high",
    "assigned_to": 2,
    "parent_id": null,
    "status": "pending"
}
```

### Voir une tâche
```http
GET /api/v1/tasks/{id}
Authorization: Bearer {token}
```

### Modifier une tâche
```http
PUT /api/v1/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Tâche Modifiée",
    "status": "in-progress"
}
```

### Supprimer une tâche
```http
DELETE /api/v1/tasks/{id}
Authorization: Bearer {token}
```

### Mettre à jour le statut d'une tâche
```http
PUT /api/v1/tasks/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "completed"
}
```

---

## 🏢 Entreprises (Super Admin uniquement)

### Lister les entreprises
```http
GET /api/v1/companies
Authorization: Bearer {token}
```

### Créer une entreprise
```http
POST /api/v1/companies
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Mon Entreprise",
    "email": "contact@entreprise.com",
    "phone": "+33123456789",
    "address": "123 Rue Example",
    "website": "https://entreprise.com",
    "admin_id": 1,
    "plan": "starter",
    "max_users": 10,
    "status": "active"
}
```

### Voir une entreprise
```http
GET /api/v1/companies/{id}
Authorization: Bearer {token}
```

### Modifier une entreprise
```http
PUT /api/v1/companies/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Entreprise Modifiée",
    "plan": "growth"
}
```

### Supprimer une entreprise
```http
DELETE /api/v1/companies/{id}
Authorization: Bearer {token}
```

### Utilisateurs d'une entreprise
```http
GET /api/v1/companies/{id}/users
Authorization: Bearer {token}
```

### Statistiques d'une entreprise
```http
GET /api/v1/companies/{id}/stats
Authorization: Bearer {token}
```

---

## 💳 Abonnements

### Informations d'abonnement
```http
GET /api/v1/subscriptions
Authorization: Bearer {token}
```

### Plans disponibles
```http
GET /api/v1/subscriptions/plans
Authorization: Bearer {token}
```

### Annuler l'abonnement
```http
PUT /api/v1/subscriptions/cancel
Authorization: Bearer {token}
```

---

## 🎫 Tickets Support

### Lister les tickets
```http
GET /api/v1/tickets
Authorization: Bearer {token}
```

### Créer un ticket
```http
POST /api/v1/tickets
Authorization: Bearer {token}
Content-Type: application/json

{
    "objet": "Problème avec l'application",
    "description": "Description détaillée du problème"
}
```

### Voir un ticket
```http
GET /api/v1/tickets/{id}
Authorization: Bearer {token}
```

### Modifier un ticket
```http
PUT /api/v1/tickets/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "objet": "Nouveau objet",
    "statut": "ferme"
}
```

### Supprimer un ticket
```http
DELETE /api/v1/tickets/{id}
Authorization: Bearer {token}
```

### Répondre à un ticket (Super Admin)
```http
POST /api/v1/tickets/{id}/respond
Authorization: Bearer {token}
Content-Type: application/json

{
    "reponse": "Voici la réponse à votre ticket",
    "statut": "ferme"
}
```

### Fermer un ticket
```http
PUT /api/v1/tickets/{id}/close
Authorization: Bearer {token}
```

### Rouvrir un ticket
```http
PUT /api/v1/tickets/{id}/reopen
Authorization: Bearer {token}
```

### Statistiques des tickets (Super Admin)
```http
GET /api/v1/tickets/stats
Authorization: Bearer {token}
```

---

## 📄 Exports

### Exporter les projets
```http
GET /api/v1/exports/projects
Authorization: Bearer {token}
```

### Exporter les tâches
```http
GET /api/v1/exports/tasks
Authorization: Bearer {token}
```

### Exporter le dashboard
```http
GET /api/v1/exports/dashboard
Authorization: Bearer {token}
```

### Formats d'export disponibles
```http
GET /api/v1/exports/formats
Authorization: Bearer {token}
```

### Exporter les données d'entreprise (Super Admin)
```http
GET /api/v1/exports/companies/{id}
Authorization: Bearer {token}
```

---

## 🔒 Permissions et Rôles

### Rôles disponibles :
- `user_independant` : Utilisateur indépendant
- `user_entreprise` : Employé d'entreprise
- `admin_entreprise` : Administrateur d'entreprise
- `super_admin` : Super administrateur

### Permissions par rôle :

#### Utilisateur Indépendant
- ✅ Gérer ses propres projets et tâches
- ✅ Créer des tickets support
- ✅ Exporter ses données (si Premium)
- ❌ Voir les données d'autres utilisateurs

#### Employé d'Entreprise
- ✅ Voir les projets et tâches de son entreprise
- ✅ Créer des tickets support
- ✅ Exporter les données (si autorisé par l'admin)
- ❌ Gérer les utilisateurs de l'entreprise

#### Administrateur d'Entreprise
- ✅ Gérer tous les projets et tâches de son entreprise
- ✅ Gérer les utilisateurs de son entreprise
- ✅ Voir les statistiques de l'entreprise
- ✅ Exporter toutes les données de l'entreprise

#### Super Administrateur
- ✅ Accès complet à toutes les données
- ✅ Gérer toutes les entreprises
- ✅ Répondre aux tickets support
- ✅ Exporter toutes les données

---

## 📝 Codes de Statut

- `200` : Succès
- `201` : Créé avec succès
- `204` : Supprimé avec succès
- `400` : Requête invalide
- `401` : Non authentifié
- `403` : Accès non autorisé
- `404` : Ressource non trouvée
- `422` : Erreur de validation
- `500` : Erreur serveur

---

## 🔧 Configuration

### Variables d'environnement requises :
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DRIVER=database
```

### Headers requis :
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## 🚀 Exemples d'utilisation

### JavaScript (Fetch)
```javascript
// Connexion
const response = await fetch('/api/v1/auth/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password'
    })
});

const data = await response.json();
const token = data.token;

// Utiliser le token pour les requêtes suivantes
const projects = await fetch('/api/v1/projects', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});
```

### PHP (cURL)
```php
// Connexion
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/v1/auth/login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'email' => 'user@example.com',
    'password' => 'password'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$data = json_decode($response, true);
$token = $data['token'];

// Utiliser le token
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/v1/projects');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
```

---

## 📞 Support

Pour toute question concernant l'API, contactez le support via les tickets support dans l'application.
