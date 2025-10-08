#!/bin/bash

echo "🚀 Démarrage de Laravel avec MySQL XAMPP..."

# Arrêter MySQL Homebrew
echo "🛑 Arrêt de MySQL Homebrew..."
brew services stop mysql 2>/dev/null || true

# Démarrer MySQL XAMPP
echo "📊 Démarrage de MySQL XAMPP..."
/Applications/XAMPP/xamppfiles/bin/mysqld_safe --user=mysql --datadir=/Applications/XAMPP/xamppfiles/var/mysql --socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock --pid-file=/Applications/XAMPP/xamppfiles/var/mysql/mysql.pid &

# Attendre que MySQL soit prêt
echo "⏳ Attente du démarrage de MySQL..Call to a member function is_premium() on null."
sleep 5

# Vérifier que MySQL fonctionne
echo "🔍 Vérification de MySQL XAMPP..."
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "SELECT 1;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ MySQL XAMPP est opérationnel"
else
    echo "❌ Erreur avec MySQL XAMPP - Démarrage via XAMPP Control Panel"
    echo "📋 Veuillez démarrer MySQL via le panneau de contrôle XAMPP"
    exit 1
fi

# Configurer le PATH
export PATH="/Applications/XAMPP/xamppfiles/bin:$PATH"

# Aller dans le répertoire du projet
cd /Users/justinghatas/Desktop/tfe-planify

# Nettoyer le cache Laravel
echo "🧹 Nettoyage du cache Laravel..."
php artisan config:clear
php artisan cache:clear

# Démarrer le serveur Laravel
echo "🌐 Démarrage du serveur Laravel..."
echo "📱 Votre application sera disponible sur http://localhost:8000"
echo "🗄️ phpMyAdmin sera disponible sur http://localhost/phpmyadmin"
echo ""
echo "Pour arrêter le serveur, appuyez sur Ctrl+C"
echo ""

php artisan serve
