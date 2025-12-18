<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #22c55e;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: white;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #22c55e;
        }
        .message-box {
            background-color: #f0f0f0;
            padding: 15px;
            border-left: 4px solid #22c55e;
            margin-top: 10px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouveau Message de Contact</h1>
        </div>

        <div class="content">
            <p>Bonjour,</p>

            <p>Vous avez reçu un nouveau message de contact. Voici les détails:</p>

            <div class="field">
                <span class="label">Nom:</span><br>
                {{ $nom }}
            </div>

            <div class="field">
                <span class="label">Email:</span><br>
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </div>

            <div class="field">
                <span class="label">Message:</span>
                <div class="message-box">
                    {!! nl2br(e($message)) !!}
                </div>
            </div>

            <p style="margin-top: 20px; color: #666;">
                Vous pouvez répondre directement à cet email pour contacter l'expéditeur.
            </p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé depuis le formulaire de contact de votre site Marathon.</p>
        </div>
    </div>
</body>
</html>

