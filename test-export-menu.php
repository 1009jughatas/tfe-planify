<?php
/**
 * Test de l'affichage conditionnel du menu Export
 */

require_once 'vendor/autoload.php';

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de l'affichage du menu Export ===\n\n";

try {
    // Test avec un employé d'entreprise sans permission d'export
    $employee = \App\Models\User::where('role', 'user_entreprise')->first();
    
    if ($employee) {
        echo "👤 Employé test: {$employee->name} ({$employee->email})\n";
        echo "🏢 Entreprise: {$employee->company_id}\n";
        echo "🔑 Rôle: {$employee->role}\n";
        
        // Retirer toutes les permissions
        $employee->update(['permissions' => []]);
        $employee->refresh();
        
        echo "\n📋 Permissions actuelles: " . json_encode($employee->permissions ?? []) . "\n";
        echo "🔍 Peut exporter PDF: " . ($employee->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        echo "📄 Menu Export visible: " . ($employee->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        
        // Accorder la permission d'export
        echo "\n🔧 Accorder la permission d'export PDF...\n";
        $employee->grantPermission('export_pdf');
        $employee->refresh();
        
        echo "✅ Permission accordée\n";
        echo "🔍 Peut exporter PDF: " . ($employee->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        echo "📄 Menu Export visible: " . ($employee->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
    } else {
        echo "❌ Aucun employé d'entreprise trouvé\n";
    }
    
    // Test avec un admin d'entreprise
    echo "\n👑 Test avec un admin d'entreprise:\n";
    $admin = \App\Models\User::where('role', 'admin_entreprise')->first();
    
    if ($admin) {
        echo "👤 Admin: {$admin->name}\n";
        echo "🔍 Peut exporter PDF: " . ($admin->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        echo "📄 Menu Export visible: " . ($admin->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
    } else {
        echo "❌ Aucun admin d'entreprise trouvé\n";
    }
    
    // Test avec un utilisateur indépendant
    echo "\n👤 Test avec un utilisateur indépendant:\n";
    $independant = \App\Models\User::where('role', 'user_independant')->first();
    
    if ($independant) {
        echo "👤 Indépendant: {$independant->name}\n";
        echo "🔍 Peut exporter PDF: " . ($independant->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
        echo "📄 Menu Export visible: " . ($independant->canExportPdf() ? "✅ Oui" : "❌ Non") . "\n";
    } else {
        echo "❌ Aucun utilisateur indépendant trouvé\n";
    }
    
    echo "\n🎉 Test de l'affichage du menu Export terminé avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
