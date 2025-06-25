<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public static function enviarEmail(string $destinatario, string $assunto, string $mensagemHtml): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Configuração SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USER'];
            $mail->Password   = $_ENV['MAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['MAIL_PORT'];

            $mail->CharSet  = 'UTF-8';
            $mail->Encoding = 'base64';

            // Remetente (From)
            $fromEmail = $_ENV['MAIL_FROM'] ?? $_ENV['MAIL_USER'];

            if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Endereço de remetente inválido: {$fromEmail}");
            }

            $mail->setFrom($fromEmail, 'Emprega.me');

            // Destinatário
            $mail->addAddress($destinatario);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagemHtml;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erro ao enviar email: {$mail->ErrorInfo} | Exceção: {$e->getMessage()}");
            return false;
        }
    }
}



