#!/bin/bash

# Script pour démarrer Laravel avec MySQL XAMPP

echo "🚀 Démarrage de Laravel avec MySQL XAMPP..."

# Arrêter MySQL Homebrew s'il est en cours d'exécution
brew services stop mysql 2>/dev/null || true

# Démarrer MySQL XAMPP
echo "📊 Démarrage de MySQL XAMPP..."
/Applications/XAMPP/xamppfiles/bin/mysqld_safe --user=mysql --datadir=/Applications/XAMPP/xamppfiles/var/mysql &

# Attendre que MySQL XAMPP soit prêt
sleep 3

# Vérifier que MySQL XAMPP fonctionne
echo "🔍 Vérification de MySQL XAMPP..."
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT 1;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ MySQL XAMPP est opérationnel"
else
    echo "❌ Erreur avec MySQL XAMPP"
    exit 1
fi

# Configurer le PATH pour utiliser MySQL XAMPP
export PATH="/Applications/XAMPP/xamppfiles/bin:$PATH"

# Aller dans le répertoire du projet
cd /Users/justinghatas/Desktop/tfe-planify

# Nettoyer le cache Laravel
echo "🧹 Nettoyage du cache Laravel..."
php artisan config:clear
php artisan cache:clear

# Démarrer le serveur Laravel
echo "🌐 Démarrage du serveur Laravel..."
php artisan serve
