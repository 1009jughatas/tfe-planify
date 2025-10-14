<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation à rejoindre {{ $company->name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }
        .content {
            background: white;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 12px 12px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }
        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .company-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .logo {
            width: 50px;
            height: 50px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 5px 0;
        }
        .role-admin {
            background: #fef3c7;
            color: #92400e;
        }
        .role-employee {
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Planify" style="width: 30px; height: 30px; filter: brightness(0) invert(1);">
        </div>
        <h1 style="margin: 0; font-size: 24px;">Vous êtes invité(e) !</h1>
        <p style="margin: 10px 0 0; opacity: 0.9;">Rejoignez l'équipe de {{ $company->name }} sur Planify</p>
    </div>

    <div class="content">
        <h2>Bonjour,</h2>
        
        <p>{{ $invitedBy->name }} vous invite à rejoindre l'équipe de <strong>{{ $company->name }}</strong> sur Planify, notre plateforme de gestion de projets.</p>

        <div class="company-info">
            <h3 style="margin-top: 0; color: #1f2937;">🏢 Informations sur l'entreprise</h3>
            <p><strong>Nom :</strong> {{ $company->name }}</p>
            <p><strong>Inviteur :</strong> {{ $invitedBy->name }} ({{ $invitedBy->email }})</p>
            <p><strong>Votre rôle :</strong> 
                <span class="role-badge {{ $invitation->role === 'admin_entreprise' ? 'role-admin' : 'role-employee' }}">
                    {{ $invitation->role === 'admin_entreprise' ? '👑 Administrateur' : '👤 Employé' }}
                </span>
            </p>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #1e40af;">🎯 Que pouvez-vous faire avec Planify ?</h3>
            <ul>
                @if($invitation->role === 'admin_entreprise')
                    <li>Gérer les projets et équipes de votre entreprise</li>
                    <li>Inviter et gérer les employés</li>
                    <li>Accéder aux statistiques et rapports avancés</li>
                    <li>Gérer les abonnements et facturations</li>
                @else
                    <li>Participer aux projets qui vous sont assignés</li>
                    <li>Créer et gérer vos tâches</li>
                    <li>Collaborer avec votre équipe</li>
                    <li>Suivre la progression des projets</li>
                @endif
            </ul>
        </div>

        <p>Pour accepter cette invitation et créer votre compte, cliquez sur le bouton ci-dessous :</p>

        <div style="text-align: center;">
            <a href="{{ $acceptUrl }}" class="button">
                ✅ Accepter l'invitation
            </a>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #dc2626;">⏰ Important</h3>
            <p><strong>Cette invitation expire le {{ \Carbon\Carbon::parse($invitation->expires_at)->format('d/m/Y à H:i') }}</strong>.</p>
            <p>Si vous ne l'acceptez pas avant cette date, vous devrez demander une nouvelle invitation.</p>
        </div>

        <p>Si vous ne souhaitez pas rejoindre cette entreprise, vous pouvez ignorer cet email en toute sécurité.</p>

        <p>Si vous avez des questions, n'hésitez pas à contacter {{ $invitedBy->name }} à l'adresse {{ $invitedBy->email }}.</p>

        <p>Cordialement,<br>
        L'équipe Planify</p>
    </div>

    <div class="footer">
        <p style="margin: 0; font-size: 12px; color: #6b7280;">
            Cet email a été envoyé automatiquement par Planify.<br>
            Si vous pensez avoir reçu cet email par erreur, vous pouvez l'ignorer en toute sécurité.
        </p>
    </div>
</body>
</html>
