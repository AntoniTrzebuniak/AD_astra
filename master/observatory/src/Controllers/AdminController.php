<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\View;
use App\Models\CelestialObject;
use App\Models\OccultationEvent;
use PDO;

class AdminController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        Auth::requireAdmin();
        $objectModel = new CelestialObject($this->db);
        $eventModel = new OccultationEvent($this->db);
        View::render('admin/index', [
            'title' => 'Panel administratora',
            'objects' => $objectModel->all(),
            'events' => $eventModel->all(),
        ]);
    }

    public function createObject(): void
    {
        Auth::requireAdmin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('admin'));
        }
        $objectModel = new CelestialObject($this->db);
        $objectModel->create([
            'designation' => trim($_POST['designation'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'type' => $_POST['type'] ?? 'asteroid',
            'magnitude' => $_POST['magnitude'] !== '' ? (float) $_POST['magnitude'] : null,
            'diameter_km' => $_POST['diameter_km'] !== '' ? (float) $_POST['diameter_km'] : null,
            'notes' => trim($_POST['notes'] ?? ''),
        ]);
        flash('success', 'Obiekt dodany.');
        redirect(baseUrl('admin'));
    }

    public function createEvent(): void
    {
        Auth::requireAdmin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('admin'));
        }
        $eventModel = new OccultationEvent($this->db);
        $eventModel->create([
            'object_id' => (int) $_POST['object_id'],
            'star_designation' => trim($_POST['star_designation'] ?? ''),
            'star_magnitude' => $_POST['star_magnitude'] !== '' ? (float) $_POST['star_magnitude'] : null,
            'predicted_time_utc' => toMysqlDatetime($_POST['predicted_time_utc'] ?? ''),
            'duration_ms' => (int) ($_POST['duration_ms'] ?? 5000),
            'path_uncertainty_km' => $_POST['path_uncertainty_km'] !== '' ? (float) $_POST['path_uncertainty_km'] : null,
            'magnitude_drop' => $_POST['magnitude_drop'] !== '' ? (float) $_POST['magnitude_drop'] : null,
        ]);
        flash('success', 'Zdarzenie zakrycia dodane.');
        redirect(baseUrl('admin'));
    }
}
