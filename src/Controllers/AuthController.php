<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Domain\User;
use App\Repository\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class AuthController
{
    public function __construct(private UserRepository $userRepository) {}

    public function login(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if (!$email || !$password) {
            header('Location: /?error=missing_fields');
            return;
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->passwordHash)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_role'] = $user->role;
            $_SESSION['user_permissions'] = $user->permissions;

            $userDataJson = json_encode([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);

            Logger::info("Login efetuado com sucesso", [
                'email' => $email,
                'user_id' => $user->id,
                'role' => $user->role
            ]);

            require __DIR__ . '/../../views/auth/store_session.php';
            return;
        }

        Logger::warning("Tentativa de login falha", [
            'email_attempt' => $email,
            'reason' => $user ? 'Senha incorreta' : 'Usuário não encontrado'
        ]);

        header('Location: /?error=invalid_credentials');
    }

    public function register(): void
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password');
        $passwordConf = filter_input(INPUT_POST, 'password_confirmation');

        if (!$name || !$email || !$password || !$passwordConf) {
            header('Location: /?error=missing_fields');
            return;
        }

        if ($password !== $passwordConf) {
            header('Location: /?error=password_mismatch');
            return;
        }

        if ($this->userRepository->findByEmail($email)) {
            header('Location: /?error=email_exists');
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $newUser = new User(null, $name, $email, $phone, $passwordHash);

        if ($this->userRepository->create($newUser)) {
            header('Location: /?success=registered');
        } else {
            header('Location: /?error=registration_failed');
        }
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        require __DIR__ . '/../../views/auth/clear_session.php';
    }

    public function forgotPassword(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (!$email) {
            header('Location: /?error=missing_email');
            return;
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(16));
            $baseUrl = $_ENV['APP_URL'];
            $resetLink = "{$baseUrl}/reset-password?token={$token}&email={$email}";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['SMTP_USER'];
                $mail->Password   = $_ENV['SMTP_PASSWORD'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $_ENV['SMTP_PORT'];
                $mail->CharSet    = 'UTF-8';
                $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_FROM']);
                $mail->addAddress($user->email, $user->name);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperação de Senha - Center Ferramentas';

                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; color: #333;'>
                        <h1>Olá, {$user->name}!</h1>
                        <p>Recebemos uma solicitação para redefinir sua senha.</p>
                        <p>Clique no botão abaixo para criar uma nova senha:</p>
                        <p>
                            <a href='{$resetLink}' style='background-color: #ff7b00; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                                Redefinir Minha Senha
                            </a>
                        </p>
                        <p>Ou copie este link no navegador:</p>
                        <p>{$resetLink}</p>
                        <hr>
                        <small>Se você não solicitou isso, ignore este e-mail.</small>
                    </div>
                ";

                $mail->AltBody = "Olá, {$user->name}. Use este link para redefinir sua senha: {$resetLink}";

                $mail->send();
            } catch (Exception $e) {
                Logger::error("Erro ao enviar e-mail de recuperação de senha", [
                    'email' => $email,
                    'phpmailer_error' => $mail->ErrorInfo,
                    'exception' => $e->getMessage()
                ]);
                header('Location: /?error=email_send_failed');
                return;
            }
        }

        header('Location: /?success=reset_email_sent');
    }
}
