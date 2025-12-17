<?php

namespace App\Controllers;

use League\OAuth2\Client\Provider\Google;

class GoogleController
{
    public function redirectToGoogle()
    {
        $config = require __DIR__ . '/../../config/google.php';

        $provider = new Google([
            'clientId'     => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'redirectUri'  => $config['redirect_uri']
        ]);

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['email', 'profile'],
            'prompt' => 'select_account'
        ]);


        $_SESSION['oauth2state'] = $provider->getState();

        header('Location: ' . $authUrl);
        exit;
    }

    public function callback()
    {
        // Validate state (CSRF)
        if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
            $_SESSION['flash_error'] = 'Invalid state parameter';
            header('Location: /auth/login');
            exit;
        }

        if (isset($_GET['error']) || !isset($_GET['code'])) {
            $_SESSION['flash_error'] = 'Không thể đăng nhập bằng Google';
            header('Location: /auth/login');
            exit;
        }

        $config = require __DIR__ . '/../../config/google.php';
        $provider = new Google([
            'clientId'     => $config['client_id'],
            'clientSecret' => $config['client_secret'],
            'redirectUri'  => $config['redirect_uri']
        ]);

        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            $googleUser = $provider->getResourceOwner($token);
            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $name = $googleUser->getName();

            $pdo = \Database::getInstance();

            // 1. Tìm user theo google_id
            $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
            $stmt->execute([$googleId]);
            $user = $stmt->fetch();

            if (!$user) {
                // 2. Tìm user theo email (account linking)
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user) {
                    // Link existing account với Google
                    $stmt = $pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                    $stmt->execute([$googleId, $user['id']]);
                } else {
                    // Tạo user mới
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, google_id, role) VALUES (?, ?, ?, 'customer')");
                    $stmt->execute([$name, $email, $googleId]);

                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$pdo->lastInsertId()]);
                    $user = $stmt->fetch();
                }
            }

            // Set session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'phone' => $user['phone'] ?? null,
                'address' => $user['address'] ?? null
            ];

            $_SESSION['flash_success'] = 'Đăng nhập Google thành công!';
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Lỗi đăng nhập Google: ' . $e->getMessage();
            header('Location: /auth/login');
            exit;
        }
    }
}
