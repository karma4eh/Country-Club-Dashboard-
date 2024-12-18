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

// Definir encabezados para JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alertType = $_POST['alert_type']; // Tipo de alerta
    $memberId = $_POST['member_id']; // Cedula del socio
    $subject = $_POST['subject']; // Asunto del correo
    $message = $_POST['message']; // Mensaje del correo

    // Cargar la plantilla de correo
    ob_start();
    include 'email_template.php';
    $bodyContent = ob_get_clean();

    $bodyContent = str_replace("<?php echo \$message; ?>", htmlspecialchars($message), $bodyContent);

    // Configuración del correo
    $mail = new PHPMailer(true);
    $emailsSent = 0; // Contador de correos enviados

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'countryclub4he@gmail.com';
        $mail->Password = 'stnn fpmg awbs trzf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('countryclub4he@gmail.com', 'Country Club');

        // Lógica para destinatarios
        if ($alertType == 'morosos') {
            $sql = "SELECT correo FROM socios WHERE saldo < 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $mail->addAddress($row['correo']);
                    $emailsSent++;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No hay socios morosos.']);
                exit;
            }
        } elseif ($alertType == 'todos') {
            $sql = "SELECT correo FROM socios";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $mail->addAddress($row['correo']);
                    $emailsSent++;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No hay socios registrados.']);
                exit;
            }
        } elseif ($alertType == 'especifico' && !empty($memberId)) {
            $sql = "SELECT correo FROM socios WHERE cedula = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $memberId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $mail->addAddress($row['correo']);
                $emailsSent++;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró el socio especificado.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tipo de alerta no válido o faltan datos.']);
            exit;
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyContent;

        // Enviar el correo
        if ($emailsSent > 0) {
            $mail->send();
            echo json_encode([
                'status' => 'success',
                'message' => "Correos enviados satisfactoriamente a {$emailsSent} destinatarios."
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se enviaron correos. Verifique los datos e intente nuevamente.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error al enviar los correos: {$mail->ErrorInfo}"]);
    }
}
?>
