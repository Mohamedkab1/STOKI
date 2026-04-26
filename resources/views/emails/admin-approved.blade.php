<!DOCTYPE html>
<html>
<head>
    <title>Compte Approuvé</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #4f46e5;">Bienvenue sur Stoki ERP !</h2>
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        <p>Nous avons le plaisir de vous informer que votre demande de compte administrateur sur la plateforme <strong>Stoki ERP</strong> a été approuvée.</p>
        <p>Vous pouvez dès maintenant vous connecter à votre espace en utilisant votre adresse email :</p>
        <p style="background: #f9fafb; padding: 10px; border-radius: 5px; display: inline-block;">
            <strong>Email :</strong> {{ $user->email }}
        </p>
        <div style="margin-top: 30px;">
            <a href="{{ route('login') }}" style="background: #4f46e5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Se connecter
            </a>
        </div>
        <p style="margin-top: 40px; font-size: 12px; color: #666;">
            Ceci est un message automatique, merci de ne pas y répondre.
        </p>
    </div>
</body>
</html>
