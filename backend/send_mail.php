<?php
include 'db_connection.php'; // Conexión a la base de datos
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/phpmailer/src/Exception.php';
require '../phpmailer/phpmailer/src/PHPMailer.php';
require '../phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alertType = $_POST['alert_type']; // Tipo de alerta: 'morosos', 'todos', o 'especifico'
    $memberId = $_POST['member_id']; // Cedula del socio
    $subject = $_POST['subject']; // Asunto del correo
    $message = $_POST['message']; // Mensaje del correo

    // Cargar la plantilla de correo
    ob_start(); // Inicia el almacenamiento en búfer de salida
    include 'email_template.php'; // Incluir la plantilla HTML
    $bodyContent = ob_get_clean(); // Obtener el contenido generado por la plantilla

    // Reemplazar el marcador de lugar de mensaje con el contenido dinámico
    $bodyContent = str_replace("<?php echo \$message; ?>", $message, $bodyContent);

    // Establecer la configuración del correo
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2; // Modo de depuración
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'countryclub4he@gmail.com';
        $mail->Password = 'stnn fpmg awbs trzf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('countryclub4he@gmail.com', 'Country Club');
        
        // Lógica para determinar los destinatarios
        if ($alertType == 'morosos') {
            // Consultar los correos de los socios morosos desde la base de datos
            // Supongamos que la tabla socios tiene un campo 'deuda' para identificar a los morosos
            $sql = "SELECT correo FROM socios WHERE saldo > 0";
            $result = $conn->query($sql); // Ejecutar la consulta

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $mail->addAddress($row['correo']); // Agregar la dirección de correo de cada moroso
                }
            }
        } elseif ($alertType == 'todos') {
            // Consultar los correos de todos los socios
            $sql = "SELECT correo FROM socios";
            $result = $conn->query($sql); // Ejecutar la consulta

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $mail->addAddress($row['correo']); // Agregar la dirección de correo de cada socio
                }
            }
        } elseif ($alertType == 'especifico' && $memberId != '') {
            // Enviar correo a un socio específico usando su cédula
            $sql = "SELECT correo FROM socios WHERE cedula = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $memberId); // Si la cédula es un VARCHAR, usa "s" en lugar de "i"
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $mail->addAddress($row['correo']); // Agregar el correo del socio específico
            } else {
                echo "No se encontró ningún socio con la cédula especificada.";
                return; // Detenemos la ejecución si no se encuentra el socio
            }
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyContent; // Usar el contenido con la plantilla HTML

        // Enviar el correo
        $mail->send();
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}
?>
