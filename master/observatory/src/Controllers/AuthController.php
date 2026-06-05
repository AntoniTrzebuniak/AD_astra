<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\View;
use App\Models\Station;
use App\Models\User;
use PDO;

class AuthController
{
    public function __construct(private PDO $db)
    {
    }

    public function loginForm(): void
    {
        View::render('auth/login', ['title' => 'Logowanie']);
    }

    public function login(): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('login'));
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('error', 'Nieprawidłowy email lub hasło.');
            redirect(baseUrl('login'));
        }

        Auth::login($user);
        flash('success', 'Zalogowano pomyślnie.');
        redirect(baseUrl());
    }

    public function registerForm(): void
    {
        View::render('auth/register', ['title' => 'Rejestracja stacji']);
    }

    public function register(): void
    {
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('register'));
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $stationName = trim($_POST['station_name'] ?? '');
        $lat = $_POST['latitude'] ?? '';
        $lng = $_POST['longitude'] ?? '';

        if ($email === '' || $password === '' || $name === '' || $stationName === '') {
            flash('error', 'Wypełnij wszystkie wymagane pola.');
            redirect(baseUrl('register'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Nieprawidłowy adres email.');
            redirect(baseUrl('register'));
        }

        if (strlen($password) < 6) {
            flash('error', 'Hasło musi mieć co najmniej 6 znaków.');
            redirect(baseUrl('register'));
        }

        $userModel = new User($this->db);
        if ($userModel->emailExists($email)) {
            flash('error', 'Ten email jest już zarejestrowany.');
            redirect(baseUrl('register'));
        }

        $userId = $userModel->create($email, $password, $name);
        $stationModel = new Station($this->db);
        $stationId = $stationModel->create($userId, [
            'name' => $stationName,
            'latitude' => (float) $lat,
            'longitude' => (float) $lng,
            'altitude_m' => (int) ($_POST['altitude_m'] ?? 0),
            'equipment' => trim($_POST['equipment'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);

        $user = $userModel->findById($userId);
        $user['station_id'] = $stationId;
        Auth::login($user);
        flash('success', 'Konto i stacja zostały utworzone.');
        redirect(baseUrl('station/edit'));
    }

    public function logout(): void
    {
        Auth::logout();
        redirect(baseUrl());
    }
}
