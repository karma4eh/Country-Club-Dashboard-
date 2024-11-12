<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Club</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f8f8f8;
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px 0;
        }
        .email-container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #FFD700;
            text-align: center;
            padding: 30px 20px;
            border-radius: 8px 8px 0 0;
        }
        .email-header img {
            max-width: 180px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .email-body {
            padding: 30px;
            text-align: center;
        }
        .email-body h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .email-body p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .cta-button {
            display: inline-block;
            background-color: #FFD700;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .cta-button:hover {
            background-color: #cc9b00;
        }
        .email-footer {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer a {
            color: #FFD700;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="https://i.ibb.co/PzxLCdJ/logo.png" alt="Logo de Country Club">
        </div>

        <body>
    <div class="email-container">

 <!-- Body -->
 <div class="email-body">
            <h2>Bienvenido a Country Club</h2>
            <p>Estimado miembro,</p>

            <!-- Mensaje dinámico -->
            <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">
                <!-- Aquí se insertará el mensaje específico para el socio o grupo -->
                <?php echo $message; ?>
            </p>

            <!-- Línea separadora -->
            <div class="separator"></div>

            <!-- Mensaje estático -->
            <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">
                Nos complace darle las gracias a nuestra familia de Country Club. En nuestro club, nos esforzamos por brindar una experiencia única y exclusiva que promueva el bienestar, la diversión y la conexión con la naturaleza.
            </p>
            <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">
                Como miembro, tendrás acceso a servicios premium, actividades recreativas y un ambiente ideal para disfrutar con tus seres queridos. Esperamos que tu experiencia con nosotros sea memorable.
            </p>
            <p style="font-size: 16px; color: #555; margin-bottom: 20px; line-height: 1.5;">
                Si tienes alguna pregunta, no dudes en contactarnos. ¡Estamos aquí para ayudarte!
            </p>

            <!-- Botón -->
            <a href="https://www.countryclub.com" class="cta-button">Descubre más</a>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; 2024 Country Club | Todos los derechos reservados</p>
            <p><strong>Este es un correo automático, por favor no respondas a este mensaje.</strong></p>
            <p>Visítanos en: <a href="https://www.countryclub.com" target="_blank">www.countryclub.com</a></p>
        </div>
    </div>
</body>
</html>