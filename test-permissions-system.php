<?php
/**
 * Test du système de permissions pour les employés d'entreprise
 */

require_once 'vendor/autoload.php';

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test du système de permissions ===\n\n";

try {
    // Récupérer un utilisateur d'entreprise
    $user = \App\Models\User::where('role', 'user_entreprise')->first();
    
    if (!$user) {
        echo "❌ Aucun utilisateur d'entreprise trouvé\n";
        exit;
    }
    
    echo "👤 Utilisateur test: {$user->name} ({$user->email})\n";
    echo "🏢 Entreprise: {$user->company_id}\n";
    echo "🔑 Rôle: {$user->role}\n";
    
    // Test des permissions par défaut
    echo "\n📋 Test des permissions par défaut:\n";
    echo "- Peut exporter PDF: " . ($user->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
    echo "- Peut créer des projets: " . ($user->hasPermission('create_projects') ? "✅ Oui" : "❌ Non") . "\n";
    echo "- Peut gérer les tâches: " . ($user->hasPermission('manage_tasks') ? "✅ Oui" : "❌ Non") . "\n";
    
    // Accorder la permission d'export PDF
    echo "\n🔧 Accorder la permission d'export PDF...\n";
    $user->grantPermission('export_pdf');
    $user->refresh();
    
    echo "✅ Permission accordée\n";
    echo "- Peut exporter PDF: " . ($user->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
    
    // Accorder d'autres permissions
    echo "\n🔧 Accorder d'autres permissions...\n";
    $user->grantPermission('create_projects');
    $user->grantPermission('manage_tasks');
    $user->refresh();
    
    echo "✅ Permissions accordées\n";
    echo "- Peut créer des projets: " . ($user->hasPermission('create_projects') ? "✅ Oui" : "❌ Non") . "\n";
    echo "- Peut gérer les tâches: " . ($user->hasPermission('manage_tasks') ? "✅ Oui" : "❌ Non") . "\n";
    
    // Afficher toutes les permissions
    echo "\n📊 Permissions actuelles:\n";
    $permissions = $user->permissions ?? [];
    foreach ($permissions as $permission) {
        echo "- {$permission}\n";
    }
    
    // Retirer une permission
    echo "\n🔧 Retirer la permission d'export PDF...\n";
    $user->revokePermission('export_pdf');
    $user->refresh();
    
    echo "✅ Permission retirée\n";
    echo "- Peut exporter PDF: " . ($user->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
    
    // Test avec un admin d'entreprise
    echo "\n👑 Test avec un admin d'entreprise:\n";
    $admin = \App\Models\User::where('role', 'admin_entreprise')->first();
    
    if ($admin) {
        echo "👤 Admin: {$admin->name}\n";
        echo "- Peut exporter PDF: " . ($admin->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        echo "- Peut créer des projets: " . ($admin->hasPermission('create_projects') ? "✅ Oui" : "❌ Non") . "\n";
        echo "- Peut gérer les tâches: " . ($admin->hasPermission('manage_tasks') ? "✅ Oui" : "❌ Non") . "\n";
    } else {
        echo "❌ Aucun admin d'entreprise trouvé\n";
    }
    
    // Test avec un utilisateur indépendant
    echo "\n👤 Test avec un utilisateur indépendant:\n";
    $independant = \App\Models\User::where('role', 'user_independant')->first();
    
    if ($independant) {
        echo "👤 Indépendant: {$independant->name}\n";
        echo "- Peut exporter PDF: " . ($independant->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        echo "- Peut créer des projets: " . ($independant->hasPermission('create_projects') ? "✅ Oui" : "❌ Non") . "\n";
    } else {
        echo "❌ Aucun utilisateur indépendant trouvé\n";
    }
    
    echo "\n🎉 Test du système de permissions terminé avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
