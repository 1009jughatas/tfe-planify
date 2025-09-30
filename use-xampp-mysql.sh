#!/bin/bash
# Script pour utiliser MySQL XAMPP avec Laravel

# Ajouter MySQL XAMPP au PATH
export PATH="/Applications/XAMPP/xamppfiles/bin:$PATH"

# Vérifier que MySQL XAMPP est utilisé
echo "Utilisation de MySQL XAMPP:"
which mysql

# Démarrer Laravel avec MySQL XAMPP
cd /Users/justinghatas/Desktop/tfe-planify
php artisan serve
