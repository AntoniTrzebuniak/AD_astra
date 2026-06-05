<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\View;
use App\Models\CelestialObject;
use App\Models\Sprint;
use App\Models\Station;
use PDO;

class SprintController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        $sprintModel = new Sprint($this->db);
        $sprintModel->updateStatuses();
        View::render('sprints/index', [
            'title' => 'Sprinty obserwacyjne',
            'active' => $sprintModel->all('active'),
            'upcoming' => $sprintModel->all('upcoming'),
            'completed' => $sprintModel->all('completed'),
        ]);
    }

    public function createForm(): void
    {
        Auth::requireLogin();
        $objectModel = new CelestialObject($this->db);
        View::render('sprints/create', [
            'title' => 'Nowy sprint',
            'objects' => $objectModel->all(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('sprints/create'));
        }

        $sprintModel = new Sprint($this->db);
        $id = $sprintModel->create([
            'creator_id' => (int) Auth::id(),
            'target_object_id' => (int) $_POST['target_object_id'],
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'start_utc' => toMysqlDatetime($_POST['start_utc'] ?? ''),
            'end_utc' => toMysqlDatetime($_POST['end_utc'] ?? ''),
        ]);

        flash('success', 'Sprint utworzony.');
        redirect(baseUrl('sprints/' . $id));
    }

    public function show(int $id): void
    {
        $sprintModel = new Sprint($this->db);
        $sprintModel->updateStatuses();
        $sprint = $sprintModel->find($id);
        if (!$sprint) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Nie znaleziono']);
            return;
        }

        $registered = false;
        $stationId = Auth::stationId();
        if ($stationId) {
            $registered = $sprintModel->isRegistered($id, $stationId);
        }

        View::render('sprints/show', [
            'title' => $sprint['title'],
            'sprint' => $sprint,
            'participants' => $sprintModel->getParticipants($id),
            'registered' => $registered,
        ]);
    }

    public function uploadPhoto(int $id): void
    {
        Auth::requireLogin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('sprints/' . $id));
        }

        $stationId = Auth::stationId();
        if (!$stationId) {
            flash('error', 'Brak stacji.');
            redirect(baseUrl('station/edit'));
        }

        if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Nie wybrano pliku.');
            redirect(baseUrl('sprints/' . $id));
        }

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed, true) || $_FILES['photo']['size'] > MAX_UPLOAD_BYTES) {
            flash('error', 'Nieprawidłowy plik.');
            redirect(baseUrl('sprints/' . $id));
        }

        $ext = match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_DIR . '/' . $filename)) {
            $photoModel = new \App\Models\Photo($this->db);
            $photoModel->create([
                'sprint_id' => $id,
                'station_id' => $stationId,
                'file_path' => $filename,
                'caption' => trim($_POST['caption'] ?? 'Sprint'),
            ]);
            flash('success', 'Zdjęcie dodane do sprintu.');
        }
        redirect(baseUrl('sprints/' . $id));
    }

    public function register(int $id): void
    {
        Auth::requireLogin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('sprints/' . $id));
        }

        $stationId = Auth::stationId();
        if (!$stationId) {
            flash('error', 'Najpierw skonfiguruj stację.');
            redirect(baseUrl('station/edit'));
        }

        $sprintModel = new Sprint($this->db);
        if ($sprintModel->register($id, $stationId)) {
            flash('success', 'Zapisano do sprintu.');
        } else {
            flash('error', 'Już jesteś zapisany lub wystąpił błąd.');
        }
        redirect(baseUrl('sprints/' . $id));
    }

    public function summary(int $id): void
    {
        $sprintModel = new Sprint($this->db);
        $summary = $sprintModel->getSummary($id);
        if (empty($summary)) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Nie znaleziono']);
            return;
        }
        View::render('sprints/summary', [
            'title' => 'Podsumowanie: ' . $summary['sprint']['title'],
            'summary' => $summary,
        ]);
    }
}
