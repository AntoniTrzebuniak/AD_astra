<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\View;
use App\Models\CelestialObject;
use App\Models\Observation;
use App\Models\Photo;
use App\Models\Station;
use PDO;

class ObservationController
{
    public function __construct(private PDO $db)
    {
    }

    public function createForm(): void
    {
        Auth::requireLogin();
        $stationModel = new Station($this->db);
        $objectModel = new CelestialObject($this->db);
        $station = $stationModel->findByUserId((int) Auth::id());
        View::render('observations/create', [
            'title' => 'Nowa obserwacja',
            'station' => $station,
            'objects' => $objectModel->all(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('observations/new'));
        }

        $stationModel = new Station($this->db);
        $station = $stationModel->findByUserId((int) Auth::id());
        if (!$station) {
            flash('error', 'Brak stacji.');
            redirect(baseUrl('station/edit'));
        }

        $planId = isset($_POST['plan_id']) && $_POST['plan_id'] !== ''
            ? (int) $_POST['plan_id']
            : null;
        $durationMs = isset($_POST['duration_ms']) && $_POST['duration_ms'] !== ''
            ? (int) $_POST['duration_ms']
            : null;

        $obsModel = new Observation($this->db);
        $obsId = $obsModel->create([
            'station_id' => (int) $station['id'],
            'object_id' => (int) $_POST['object_id'],
            'plan_id' => $planId,
            'observed_at_utc' => toMysqlDatetime($_POST['observed_at_utc'] ?? gmdate('Y-m-d\TH:i')),
            'result' => $_POST['result'] ?? 'no_data',
            'duration_ms' => $durationMs,
            'notes' => trim($_POST['notes'] ?? ''),
        ]);

        if (!empty($_FILES['photos']['name'][0])) {
            $this->handlePhotoUpload($obsId, (int) $station['id']);
        }

        flash('success', 'Obserwacja zapisana.');
        redirect(baseUrl('stations/' . $station['id']));
    }

    private function handlePhotoUpload(int $observationId, int $stationId): void
    {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $photoModel = new Photo($this->db);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $files = $_FILES['photos'];

        foreach ($files['name'] as $i => $name) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            if ($files['size'][$i] > MAX_UPLOAD_BYTES) {
                continue;
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $files['tmp_name'][$i]);
            finfo_close($finfo);
            if (!in_array($mime, $allowed, true)) {
                continue;
            }
            $ext = match ($mime) {
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg',
            };
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $filename;
            if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                $photoModel->create([
                    'observation_id' => $observationId,
                    'station_id' => $stationId,
                    'file_path' => $filename,
                    'caption' => pathinfo($name, PATHINFO_FILENAME),
                ]);
            }
        }
    }
}
