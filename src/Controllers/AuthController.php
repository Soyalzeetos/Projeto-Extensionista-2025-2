<?php

namespace App\Controllers;

use App\Repository\UserRepository;

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

            require __DIR__ . '/../../views/auth/store_session.php';
            return;
        }

        header('Location: /?error=invalid_credentials');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        require __DIR__ . '/../../views/auth/clear_session.php';
    }
}
