<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #0ee027;
            color: #000;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
            color: #333;
        }
        .email-field {
            margin-bottom: 20px;
        }
        .email-field strong {
            display: block;
            color: #0ee027;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .email-field p {
            margin: 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #0ee027;
            word-wrap: break-word;
        }
        .email-message {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #0ee027;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 Nouveau message de contact</h1>
        </div>
        
        <div class="email-body">
            <div class="email-field">
                <strong>De:</strong>
                <p>{{ $data['nom'] }}</p>
            </div>

            <div class="email-field">
                <strong>Email:</strong>
                <p><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></p>
            </div>

            @if(!empty($data['sujet']))
            <div class="email-field">
                <strong>Sujet:</strong>
                <p>{{ $data['sujet'] }}</p>
            </div>
            @endif

            <div class="email-field">
                <strong>Message:</strong>
                <div class="email-message">{{ $data['message'] }}</div>
            </div>
        </div>

        <div class="email-footer">
            <p>Message envoyé depuis votre portfolio Terminal</p>
            <p>{{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>