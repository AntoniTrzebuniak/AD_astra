<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\View;
use App\Models\Photo;
use App\Models\Station;
use PDO;

class StationController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        $stationModel = new Station($this->db);
        View::render('stations/index', [
            'title' => 'Stacje obserwacyjne',
            'stations' => $stationModel->all(),
        ]);
    }

    public function show(int $id): void
    {
        $stationModel = new Station($this->db);
        $photoModel = new Photo($this->db);
        $station = $stationModel->find($id);
        if (!$station) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Nie znaleziono']);
            return;
        }
        View::render('stations/show', [
            'title' => $station['name'],
            'station' => $station,
            'observations' => $stationModel->getObservations($id),
            'photos' => $photoModel->forStation($id),
        ]);
    }

    public function editForm(): void
    {
        Auth::requireLogin();
        $stationModel = new Station($this->db);
        $station = $stationModel->findByUserId((int) Auth::id());
        if (!$station) {
            flash('error', 'Nie znaleziono stacji.');
            redirect(baseUrl());
        }
        View::render('stations/edit', [
            'title' => 'Moja stacja',
            'station' => $station,
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('station/edit'));
        }

        $stationModel = new Station($this->db);
        $station = $stationModel->findByUserId((int) Auth::id());
        if (!$station) {
            flash('error', 'Nie znaleziono stacji.');
            redirect(baseUrl());
        }

        $stationModel->update((int) $station['id'], [
            'name' => trim($_POST['name'] ?? ''),
            'latitude' => (float) ($_POST['latitude'] ?? 0),
            'longitude' => (float) ($_POST['longitude'] ?? 0),
            'altitude_m' => (int) ($_POST['altitude_m'] ?? 0),
            'equipment' => trim($_POST['equipment'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);

        flash('success', 'Profil stacji zaktualizowany.');
        redirect(baseUrl('station/edit'));
    }
}
