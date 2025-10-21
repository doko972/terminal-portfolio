<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - ERROR: PAGE NOT FOUND</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            font-family: 'Roboto Mono', monospace;
            font-weight: 300;
            line-height: 1.7;
            color: #0ee027;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Animation Matrix Background */
        #matrix-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.15;
        }

        /* Container principal */
        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            max-width: 800px;
            border: 2px solid #0ee027;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0 0 30px rgba(14, 224, 39, 0.5);
            animation: glitchBox 5s infinite;
        }

        @keyframes glitchBox {
            0%, 90%, 100% {
                transform: translate(0);
            }
            92% {
                transform: translate(-2px, 2px);
            }
            94% {
                transform: translate(2px, -2px);
            }
            96% {
                transform: translate(-2px, -2px);
            }
        }

        .terminal-header {
            text-align: left;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #0ee027;
            font-size: 0.9rem;
            text-shadow: 0 0 8px #0ee027;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            text-shadow: 0 0 20px #0ee027, 0 0 40px #0ee027;
            animation: flicker 3s infinite, glitch 5s infinite;
            margin: 2rem 0;
            letter-spacing: 1rem;
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; }
            41%, 43%, 45%, 47% { opacity: 0.8; }
            42%, 44%, 46% { opacity: 1; }
        }

        @keyframes glitch {
            0%, 90%, 100% {
                transform: translate(0);
            }
            92% {
                transform: translate(-5px, 2px);
                text-shadow: 3px 0 #ff00de, -3px 0 #00fff9;
            }
            94% {
                transform: translate(5px, -2px);
                text-shadow: -3px 0 #ff00de, 3px 0 #00fff9;
            }
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px #0ee027;
            animation: flicker 4s infinite;
        }

        h1::before {
            content: '> ';
            color: #0ee027;
        }

        .error-message {
            font-size: 1rem;
            margin: 2rem 0;
            padding: 1rem;
            background: rgba(14, 224, 39, 0.05);
            border-left: 3px solid #0ee027;
            text-align: left;
        }

        .error-message::before {
            content: '[ERROR] ';
            color: #ff0000;
            font-weight: 700;
            text-shadow: 0 0 10px #ff0000;
        }

        .terminal-prompt {
            text-align: left;
            margin: 2rem 0;
            font-size: 0.95rem;
        }

        .terminal-prompt::before {
            content: 'root@system:~$ ';
            color: #0ee027;
            font-weight: 700;
        }

        .cursor {
            display: inline-block;
            width: 10px;
            height: 20px;
            background-color: #0ee027;
            animation: blink 1s step-end infinite;
            margin-left: 5px;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border: 2px solid #0ee027;
            background: transparent;
            color: #0ee027;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.95rem;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #0ee027;
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn:hover {
            color: #000;
            box-shadow: 0 0 20px #0ee027;
            text-shadow: none;
        }

        .btn:hover::before {
            left: 0;
        }

        .btn::after {
            content: ' >';
            margin-left: 5px;
        }

        .scanlines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                rgba(0, 0, 0, 0.15),
                rgba(0, 0, 0, 0.15) 1px,
                transparent 1px,
                transparent 2px
            );
            pointer-events: none;
            z-index: 9999;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 5rem;
                letter-spacing: 0.5rem;
            }

            h1 {
                font-size: 1.3rem;
            }

            .error-message {
                font-size: 0.9rem;
            }

            .buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <!-- Canvas Matrix -->
    <canvas id="matrix-canvas"></canvas>

    <!-- Scanlines effect -->
    <div class="scanlines"></div>

    <!-- Container principal -->
    <div class="container">
        <div class="terminal-header">
            SYSTEM ERROR | {{ date('Y-m-d H:i:s') }} | STATUS: CRITICAL
        </div>

        <div class="error-code">404</div>

        <h1>PAGE NOT FOUND</h1>

        <div class="error-message">
            La ressource demandée est introuvable dans la base de données système.
            <br>Code d'erreur: HTTP_404_NOT_FOUND
            <br>Chemin: {{ Request::path() }}
        </div>

        <div class="terminal-prompt">
            Redirection disponible<span class="cursor"></span>
        </div>

        <div class="buttons">
            <a href="{{ route('login') }}" class="btn">
                Retour accueil
            </a>
            <a href="javascript:history.back()" class="btn">
                Page précédente
            </a>
        </div>
    </div>

    <script>
        // Animation Matrix Background
        const canvas = document.getElementById('matrix-canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*(){}[]<>/';
        const charArray = chars.split('');
        const fontSize = 14;
        const columns = canvas.width / fontSize;
        const drops = Array(Math.floor(columns)).fill(1);

        function drawMatrix() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.fillStyle = '#0ee027';
            ctx.font = fontSize + 'px monospace';

            for (let i = 0; i < drops.length; i++) {
                const char = charArray[Math.floor(Math.random() * charArray.length)];
                ctx.fillText(char, i * fontSize, drops[i] * fontSize);

                if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                    drops[i] = 0;
                }
                drops[i]++;
            }
        }

        setInterval(drawMatrix, 50);

        // Resize canvas
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>
