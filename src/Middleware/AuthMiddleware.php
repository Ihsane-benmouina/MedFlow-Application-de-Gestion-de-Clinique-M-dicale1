<?php

namespace App\Middleware;


class AuthMiddleware
{
  
    public static function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

   
    public static function requireRole(string $role): void
    {
        self::requireLogin();

        if ($_SESSION['user']['role'] !== $role) {
            header('Location: index.php?action=login');
            exit();
        }
    }

   
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

  
    public static function getRole(): string
    {
        return $_SESSION['user']['role'] ?? '';
    }

   
    public static function getUserId(): int
    {
        return (int) ($_SESSION['user']['id'] ?? 0);
    }
}
