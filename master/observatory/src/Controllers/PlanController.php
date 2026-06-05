<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\ConflictDetector;
use App\Helpers\View;
use App\Models\OccultationEvent;
use App\Models\ObservationPlan;
use App\Models\Station;
use PDO;

class PlanController
{
    public function __construct(private PDO $db)
    {
    }

    public function tonight(): void
    {
        Auth::requireLogin();
        $nightDate = getTonightDate();
        $stationModel = new Station($this->db);
        $eventModel = new OccultationEvent($this->db);
        $planModel = new ObservationPlan($this->db);
        $conflictDetector = new ConflictDetector($this->db);

        $station = $stationModel->findByUserId((int) Auth::id());
        if (!$station) {
            flash('error', 'Najpierw skonfiguruj swoją stację.');
            redirect(baseUrl('station/edit'));
        }

        $myPlans = $planModel->forStationNight((int) $station['id'], $nightDate);
        $allPlans = $planModel->allForNight($nightDate);
        $events = $eventModel->forTonight($nightDate);
        $suggestions = $conflictDetector->suggestFreeEvents($nightDate, (int) $station['id']);

        View::render('plan/tonight', [
            'title' => 'Plan na dziś',
            'nightDate' => $nightDate,
            'station' => $station,
            'myPlans' => $myPlans,
            'allPlans' => $allPlans,
            'events' => $events,
            'suggestions' => $suggestions,
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Nieprawidłowy token CSRF.');
            redirect(baseUrl('plan/tonight'));
        }

        $stationModel = new Station($this->db);
        $station = $stationModel->findByUserId((int) Auth::id());
        if (!$station) {
            flash('error', 'Brak stacji.');
            redirect(baseUrl('station/edit'));
        }

        $eventId = (int) ($_POST['event_id'] ?? 0);
        $nightDate = getTonightDate();
        $startUtc = toMysqlDatetime($_POST['planned_start_utc'] ?? '');
        $endUtc = toMysqlDatetime($_POST['planned_end_utc'] ?? '');

        $conflictDetector = new ConflictDetector($this->db);
        $conflicts = $conflictDetector->findConflicts(
            $eventId,
            (int) $station['id'],
            $startUtc,
            $endUtc
        );

        if (!empty($conflicts) && empty($_POST['force'])) {
            $names = array_map(fn($c) => $c['station_name'], $conflicts);
            flash('error', 'Konflikt! Inne stacje planują tę planetoidę: ' . implode(', ', $names) . '. Zaznacz "Wymuś" aby kontynuować.');
            redirect(baseUrl('plan/tonight'));
        }

        $planModel = new ObservationPlan($this->db);
        $planModel->create([
            'station_id' => (int) $station['id'],
            'event_id' => $eventId,
            'night_date' => $nightDate,
            'planned_start_utc' => $startUtc,
            'planned_end_utc' => $endUtc,
            'status' => 'planned',
            'notes' => trim($_POST['notes'] ?? ''),
        ]);

        flash('success', 'Plan obserwacji zapisany.');
        redirect(baseUrl('plan/tonight'));
    }

    public function export(): void
    {
        $nightDate = getTonightDate();
        $planModel = new ObservationPlan($this->db);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="plan_' . $nightDate . '.csv"');
        echo $planModel->exportCsv($nightDate);
        exit;
    }
}
