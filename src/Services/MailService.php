<?php
// Services/MailService.php
namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MailService
{
    private $mail;

    public function __construct()
    {
        //configuro el mailer
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host = getenv('MAIL_HOST') ?: 'mailhog';
        $this->mail->Port = getenv('MAIL_PORT') ?: 1025;
        $this->mail->SMTPAuth = false;
        $this->mail->SMTPDebug = 0;
        $this->mail->setFrom('no-reply@worksphere.com', 'WorkSphere-System');
        $this->mail->isHTML(true);
    }


    private function plantillaCorreo($titulo, $mensaje, $mensajeSecundario)
    {
        return '
            <div style="font-family: Arial, sans-serif; background-color:#f4f4f4; padding:20px;">
                <div style="
                    max-width:600px; 
                    margin:0 auto; 
                    background:#ffffff; 
                    padding:25px; 
                    border-radius:8px; 
                    box-shadow:0 2px 10px rgba(0,0,0,0.1);
                ">
                    <h2 style="color:#333; margin-bottom:15px;">' . $titulo . '</h2>

                    <p style="color:#555; font-size:15px; line-height:1.6;">
                        ' . $mensaje . '
                    </p>

                    <p style="color:#777; font-size:14px; line-height:1.6; margin-top:20px;">
                        ' . $mensajeSecundario . '
                    </p>

                    <hr style="margin:30px 0; border:0; border-top:1px solid #ddd;">

                    <p style="color:#444; font-size:14px;">
                        Saludos cordiales,<br>
                        <strong>El equipo de WorkSphere</strong>
                    </p>
                </div>
            </div>';
    }


    public function enviarCorreoAprobacion($destinatario, $nombreEmpresa)
    {
        try {
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = 'Su empresa ha sido aprobada';

            $mensaje = "Su empresa <strong>{$nombreEmpresa}</strong> ha sido aprobada en nuestro sistema.";
            $mensajeSecundario = "Ahora puede acceder a todas las funcionalidades disponibles.";

            $this->mail->Body = $this->plantillaCorreo(
                "¡Enhorabuena!",
                $mensaje,
                $mensajeSecundario
            );

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo de aprobación: " . $this->mail->ErrorInfo);
            return false;
        }
    }


    public function enviarCorreoRechazo($destinatario, $nombreEmpresa)
    {
        try {
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = 'Solicitud de empresa rechazada';

            $mensaje = "Lamentamos informarle que su empresa <strong>{$nombreEmpresa}</strong> no ha sido aprobada.";
            $mensajeSecundario = "Si considera que esto es un error, por favor contacte con el administrador.";

            $this->mail->Body = $this->plantillaCorreo(
                "Información importante",
                $mensaje,
                $mensajeSecundario
            );

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo de rechazo: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}
