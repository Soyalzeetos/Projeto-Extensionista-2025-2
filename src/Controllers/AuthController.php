<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Core\Mailer;
use App\Domain\User;
use App\Repository\UserRepository;

class AuthController
{
    public function __construct(
        private UserRepository $userRepository,
        private Mailer $mailer
    ) {}

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

            Logger::info("Login successful", [
                'email' => $email,
                'user_id' => $user->id,
                'role' => $user->role
            ]);

            require __DIR__ . '/../../views/auth/store_session.php';
            return;
        }

        Logger::warning("Login attempt failed", [
            'email_attempt' => $email,
            'reason' => $user ? 'Incorrect password' : 'User not found'
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

        $verificationToken = bin2hex(random_bytes(32));

        $newUser = new User(null, $name, $email, $phone, $passwordHash);

        if ($this->userRepository->create($newUser, $verificationToken)) {
            $this->sendWelcomeEmail($name, $email, $verificationToken);
            header('Location: /?success=registered_check_email');
        } else {
            header('Location: /?error=registration_failed');
        }
    }

    public function verifyEmail(): void
    {
        $token = filter_input(INPUT_GET, 'token');

        if (!$token) {
            header('Location: /?error=invalid_token');
            return;
        }

        $success = $this->userRepository->verifyEmail($token);

        require __DIR__ . '/../../views/auth/verify_email.php';
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
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->userRepository->storePasswordResetToken($email, $token, $expiresAt);

            $baseUrl = $_ENV['APP_URL'];
            $resetLink = "{$baseUrl}/reset-password?token={$token}";

            $subject = 'Recuperação de Senha - Center Ferramentas';
            $body = "
                <div style='font-family: Arial, sans-serif;'>
                    <h1>Recuperação de Senha</h1>
                    <p>Olá, {$user->name}.</p>
                    <p>Clique abaixo para redefinir:</p>
                    <a href='{$resetLink}'>Redefinir Senha</a>
                </div>
            ";

            $this->mailer->send($user->email, $user->name, $subject, $body, "Link: $resetLink");
        }

        header('Location: /?success=reset_email_sent');
    }

    private function sendWelcomeEmail(string $name, string $email, string $token): void
    {
        $subject = 'Confirme seu cadastro na Center Ferramentas';
        $baseUrl = $_ENV['APP_URL'];

        $verificationLink = "{$baseUrl}/verify-email?token={$token}";

        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h1 style='color: #ff7b00;'>Bem-vindo, {$name}!</h1>
                <p>Obrigado por se cadastrar. Para garantir a segurança da sua conta, por favor confirme seu e-mail.</p>
                <p style='text-align: center; margin: 30px 0;'>
                    <a href='{$verificationLink}' style='background-color: #000; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>
                        Confirmar E-mail Agora
                    </a>
                </p>
                <p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:</p>
                <p style='word-break: break-all; color: #666;'>{$verificationLink}</p>
                <hr>
                <small>Equipe Center Ferramentas</small>
            </div>
        ";

        $altBody = "Bem-vindo! Confirme seu e-mail acessando: {$verificationLink}";

        $this->mailer->send($email, $name, $subject, $body, $altBody);
    }

    public function showResetForm(): void
    {
        $token = filter_input(INPUT_GET, 'token');

        if (!$token) {
            header('Location: /?error=invalid_token');
            return;
        }

        $resetRequest = $this->userRepository->findResetToken($token);

        if (!$resetRequest || strtotime($resetRequest['expires_at']) < time()) {
            header('Location: /?error=token_expired_or_invalid');
            return;
        }

        require __DIR__ . '/../../views/auth/reset_password.php';
    }

    public function resetPassword(): void
    {
        $token = filter_input(INPUT_POST, 'token');
        $password = filter_input(INPUT_POST, 'password');
        $passwordConf = filter_input(INPUT_POST, 'password_confirmation');

        if ($password !== $passwordConf) {
            header("Location: /reset-password?token=$token&error=password_mismatch");
            return;
        }

        $resetRequest = $this->userRepository->findResetToken($token);

        if (!$resetRequest || strtotime($resetRequest['expires_at']) < time()) {
            header('Location: /?error=token_invalid');
            return;
        }

        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $this->userRepository->updatePassword($resetRequest['email'], $newHash);
        $this->userRepository->deleteResetToken($token);

        header('Location: /?success=password_reset');
    }
}
