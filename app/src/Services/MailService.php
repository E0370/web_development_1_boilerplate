<?php
namespace App\Services;

use App\Services\Interfaces\IMailService;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailService implements IMailService
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $encryption;
    private string $fromAddress;
    private string $fromName;

    public function __construct()
    {
        $this->host = $_ENV['MAIL_HOST'] ?? '';
        $this->port = (int) ($_ENV['MAIL_PORT'] ?? 587);
        $this->username = $_ENV['MAIL_USERNAME'] ?? '';
        $this->password = $_ENV['MAIL_PASSWORD'] ?? '';
        $this->encryption = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
        $this->fromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? '';
        $this->fromName = $_ENV['MAIL_FROM_NAME'] ?? 'Lost&Found';
    }

    public function sendPasswordResetEmail(string $toEmail, string $resetLink)
    {
        $subject = 'Reset your password';

        $safeLink = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');

        $htmlBody = "
            <p>Dear User,</p>
            <p>You requested a password reset.</p>
            <p>Click the link below to reset your password:</p>
            <p><a href=\"{$safeLink}\">Reset Password</a></p>
            <p>If the button does not work, copy this link into your browser:</p>
            <p>{$safeLink}</p>
            <p>This link expires in 1 hour.</p>
            <p>If you did not request this, you can ignore this email.</p>
        ";

        $textBody =

            "This link expires in 1 hour.\n\n" .
            "If you did not request this, you can ignore this email.";

        $this->send($toEmail, $subject, $htmlBody, $textBody);
    }

    private function send(string $toEmail, string $subject, string $htmlBody, string $textBody)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->Port = $this->port;

            if ($this->encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($this->encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }

            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody;

            $mail->send();
        } catch (Exception $e) {
            throw new Exception('Email could not be sent:' . $e->getMessage());
        }
    }
}